# Driver Location (Real-Time)

## Overview
- Redis is the single source of truth for online driver state.
- All real-time updates are handled by a single Lua script executed atomically.
- PostgreSQL is not touched in the hot path.
- Centrifugo is used only as transport for updates.

## Components
- Redis GEO + TTL: online state, location snapshot, and proximity search.
- Lua: atomic update + publish decision.
- Laravel services: orchestrate Lua call and publish if flagged.
- Centrifugo: WS publish for region updates.

## Redis keys
- `drivers:geo` GEO index of drivers.
- `drivers:online` set of online driver IDs.
- `driver:{id}:state` status string with TTL.
- `driver:{id}:location` JSON `{lat,lng,ts}` with TTL.
- `driver:{id}:publish` JSON `{lat,lng,ts}` with TTL, used for publish throttling.

## Lua script
- File: `resources/redis/update_driver_location.lua`
- Responsibilities:
  - Update GEO position.
  - Refresh TTL for state and location.
  - Decide whether the update should be published.
  - Return `{publish, lat, lng, ts, status}` for PHP.

## PHP flow (hot path)
1. Controller validates input.
2. `DriverLocationUpdater` calls Lua with keys and publish settings.
3. If `publish == 1`, `DriverLocationPublisher` publishes to Centrifugo.
4. Response is built from the Redis snapshot.

## Nearby drivers search
- `FindNearbyDriversQuery` uses `GEORADIUS` to get candidates.
- Candidates are filtered by `driver:{id}:state == online`.

## Matching example
- `OrderMatchingService` fetches nearby drivers and publishes ride offers to
  `driver:{id}` channels via Centrifugo.
