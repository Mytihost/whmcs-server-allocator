# Server Allocator Hook for WHMCS

This hook works on configurable options Location only. When a client selects a location, the hook will allocate the same selected plan with the updated server location. If you have multiple servers in the same location, this hook will also allocate a random server for that same selected location. This hook does not currently run any auto set-ups; it only allocates a server.

## Compatibility

This hook has been tested with:
- WHMCS 8.10
- PHP 8.1
- cPanel hosting only

## Required Files

This setup requires two files:
1. The server allocator hook file.
2. A configuration file containing the server/location mappings used by the hook.

The configuration file determines which servers should be assigned to specific product groups based on the selected location. Servers must be listed by their WHMCS server IDs. Disabled servers in WHMCS are automatically skipped. If no enabled servers are available for the selected location, an error will be logged. Location keys in the configuration must exactly match the values set in the WHMCS configurable option for “Location”.

## How to Use

Only one product group is required such as Shared Hosting. The product group should only include the hosting plans that you would consider to be your default or recommended location. If your company is UK based then you would have the plans that are on your UK server as the default showing. All others can be hidden or will no longer be needed.

WHM package names must be identical on each individual server. If for example you have package names Personal and Business on your default location then they must all be named Personal and Business on each server regardless of location. Any package name mismatches will cause an error.

### Configurable Options Setup

Create a configurable option group such as “cPanel Shared Location”.

Add a configurable option named:
Location

Add option values such as:

- UK (Change this to your default location)
- Location 2
- Location 3

Assign the configurable option group to your default hosting plans.