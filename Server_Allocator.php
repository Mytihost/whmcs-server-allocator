<?php

use WHMCS\Database\Capsule;

add_hook('AfterShoppingCartCheckout', 1, function($vars) {
    // Load configuration
    $configPath = ROOTDIR . '/includes/hooks/server_allocator_config.php';
    if (!file_exists($configPath)) {
        logActivity('ServerAllocator Hook Error: Config file not found at ' . $configPath);
        return;
    }

    $config = include $configPath;

    // Validate configuration keys
    if (empty($config['productGroups'])) {
        logActivity("ServerAllocator Hook Error: Configuration missing required keys.");
        return;
    }

    // Get the order ID
    $orderId = isset($vars['OrderID']) ? (int)$vars['OrderID'] : 0;
    if ($orderId === 0) {
        logActivity('ServerAllocator Hook Error: Invalid Order ID.');
        return;
    }

    try {
        // Fetch all hosting products associated with the order
        $hostingItems = Capsule::table('tblhosting')
            ->where('orderid', $orderId)
            ->get();

        if ($hostingItems->isEmpty()) {
            logActivity("ServerAllocator Hook: No hosting items found for Order ID {$orderId}.");
            return;
        }

        foreach ($hostingItems as $hostingItem) {
            $productId = $hostingItem->packageid;

            // Fetch the product group name
            $productData = Capsule::table('tblproducts')
                ->where('tblproducts.id', $productId)
                ->join('tblproductgroups', 'tblproducts.gid', '=', 'tblproductgroups.id')
                ->select('tblproductgroups.name as productGroup', 'tblproducts.id as productId')
                ->first();

            if (!$productData || !isset($config['productGroups'][$productData->productGroup])) {
                logActivity("ServerAllocator Hook: Product Group '{$productData->productGroup}' is not configured.");
                continue;
            }

            // Validate product ID in product group
            if (!in_array($productData->productId, $config['productGroups'][$productData->productGroup]['productIds'])) {
                logActivity("ServerAllocator Hook: Product ID '{$productData->productId}' is not part of Product Group '{$productData->productGroup}'.");
                continue;
            }

            // Fetch configurable option for 'Location'
            $configOption = Capsule::table('tblhostingconfigoptions')
                ->where('relid', $hostingItem->id)
                ->join('tblproductconfigoptions', 'tblhostingconfigoptions.configid', '=', 'tblproductconfigoptions.id')
                ->join('tblproductconfigoptionssub', 'tblhostingconfigoptions.optionid', '=', 'tblproductconfigoptionssub.id')
                ->where('tblproductconfigoptions.optionname', 'Location')
                ->select('tblproductconfigoptionssub.optionname as location')
                ->first();

            if (!$configOption) {
                logActivity("ServerAllocator Hook: No 'Location' configurable option found for Hosting ID {$hostingItem->id}.");
                continue;
            }

            $location = $configOption->location;

            // Check if the location is configured for the product group
            if (!isset($config['productGroups'][$productData->productGroup]['serverIds'][$location])) {
                logActivity("ServerAllocator Hook Error: Location '{$location}' not configured for Product Group '{$productData->productGroup}'.");
                continue;
            }

            $availableServers = $config['productGroups'][$productData->productGroup]['serverIds'][$location];
            if (empty($availableServers)) {
                logActivity("ServerAllocator Hook Error: No servers available for Location '{$location}' in Product Group '{$productData->productGroup}'.");
                continue;
            }

            // Find an available server
            $serverId = Capsule::table('tblservers')
                ->whereIn('id', $availableServers)
                ->where('disabled', 0)
                ->value('id');

            if ($serverId !== null) {
                // Update the hosting record with the selected server ID
                Capsule::table('tblhosting')
                    ->where('id', $hostingItem->id)
                    ->update(['server' => $serverId]);

                logActivity("ServerAllocator Hook: Updated Hosting ID {$hostingItem->id} with Server ID {$serverId} for Location '{$location}' in Product Group '{$productData->productGroup}'.");
            } else {
                logActivity("ServerAllocator Hook Error: No enabled servers available for Location '{$location}' in Product Group '{$productData->productGroup}'.");
            }
        }

    } catch (\Exception $e) {
        logActivity('ServerAllocator Hook Exception: ' . $e->getMessage());
    }
});