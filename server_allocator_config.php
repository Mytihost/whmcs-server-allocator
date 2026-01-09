<?php

/**
 * Server Allocator Configuration File
 *
 * This configuration file is used by the Server Allocator WHMCS hook to determine which servers
 * should be assigned to specific product groups based on the selected location.
 *
 * How It Works:
 * - Each product group (e.g., "Shared Hosting") is mapped to a set of product IDs.
 * - Each product group has a list of server IDs assigned per location.
 * - When an order is placed, the hook checks the product's group and location.
 * - If a matching **enabled** server is available for the selected location, it updates the order accordingly.
 * - **Disabled servers in WHMCS are automatically skipped**. If no enabled servers are available for a location, an error is logged.
 * - Additional product groups and locations can be added by following the same structure.
 *
 * Location References:
 * - The location keys ('UK', 'USA', etc.) **must exactly match** the values set in WHMCS under 
 *   the configurable option for 'Location'. If they do not match, the hook will not be able 
 *   to assign the correct server.
 * - Locations should be added in **WHMCS ? Setup ? Product/Services ? Configurable Options** 
 *   under an option group containing the location choices.
 * - Ensure the names of the locations in this file are identical to the **option values** 
 *   stored in WHMCS for accurate server assignment.
 */

return [
    'productGroups' => [
        'Shared Hosting' => [
            'productIds' => [1, 2], // List of product IDs in this group. Only include the IDs of those for your default location
            'serverIds' => [
                'UK' => [1], // Server IDs for your default location. Change UK to your default location
                'USA' => [2]  // Server IDs for USA location
            ]
        ],

        /*
        'Reseller Hosting' => [
            'productIds' => [201, 202, 203], // List of product IDs in this group
            'serverIds' => [
                'UK' => [5, 6], // Server IDs for UK location
                'USA' => [7, 8]  // Server IDs for USA location
            ]
        ],
        */

        /*
        // Example: Single Product ID with a Single Server ID per Location
        'Shared Hosting' => [
            'productIds' => [2], // Single product ID
            'serverIds' => [
                'UK' => [1], // Only one server available in the UK
                'USA' => [2]  // Only one server available in the USA
            ]
        ],
        */

        /*
        // Example: Adding Extra Locations
        'Shared Hosting' => [
            'productIds' => [301, 302, 303, 304, 305, 306], // Product IDs for this group
            'serverIds' => [
                'UK' => [21, 22],  // Servers assigned for UK location
                'USA' => [23, 24],  // Servers assigned for USA location
                'Germany' => [25, 26], // New location: Germany
                'France' => [27, 28],  // New location: France
                'Australia' => [29, 30] // New location: Australia
            ]
        ],
        */
    ],
];