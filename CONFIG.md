# Server Allocator – Configuration File Documentation

This configuration file is used by the Server Allocator WHMCS hook to determine which servers should be assigned to specific product groups based on the selected location.

## Compatibility

This configuration file and associated hook have been tested with:
- WHMCS 8.10
- PHP 8.1
- cPanel hosting only

## How It Works

- Each product group (for example “Shared Hosting”) is mapped to a set of product IDs.
- Each product group contains a list of server IDs assigned per location.
- When an order is placed, the hook checks the product's group and the selected location from configurable options.
- If a matching enabled server is available for the selected location, the hook assigns the order to that server.
- Disabled servers are automatically skipped. If no enabled servers exist for a location, an error is logged.
- Additional product groups and locations can be added by following the same structure.

## Location References

- The location keys (e.g., UK, USA, etc.) must exactly match the option values defined in WHMCS under the Location configurable option.
- These values must be created in WHMCS under:
  Setup → Products/Services → Configurable Options
- Spelling, capitalisation and spacing must be identical.
- If they do not match, the hook will not assign the correct server.

## Requirements

- WHM package names must be identical across all servers in the group.
- Only one main product group is required, containing your default-location plans.
- Each product must have the Location configurable option assigned.
- This file must remain readable and follow the same structure for the hook to function correctly.

## Notes

- This configuration file does not perform provisioning.
- The hook uses it only to allocate the correct server.
- You may add or remove locations as long as the structure is kept consistent.