-- KEYS
-- 1 = geoKey
-- 2 = onlineSet
-- 3 = stateKey
-- 4 = locationKey
-- 5 = publishKey
--
-- ARGV
-- 1 = driverId
-- 2 = lng
-- 3 = lat
-- 4 = nowTs
-- 5 = ttl
-- 6 = publishMinInterval
-- 7 = publishMinDistanceMeters
-- 8 = publishEnabled (0/1)
local function toNumber(value, fallback)
  local num = tonumber(value)
  if num == nil then
    return fallback
  end
  return num
end

local function distanceMeters(lat1, lng1, lat2, lng2)
  local radius = 6371000.0
  local lat1Rad = math.rad(lat1)
  local lat2Rad = math.rad(lat2)
  local deltaLat = math.rad(lat2 - lat1)
  local deltaLng = math.rad(lng2 - lng1)
  local a = math.sin(deltaLat / 2) ^ 2
    + math.cos(lat1Rad) * math.cos(lat2Rad) * math.sin(deltaLng / 2) ^ 2
  local c = 2 * math.atan2(math.sqrt(a), math.sqrt(1 - a))
  return radius * c
end

local lng = toNumber(ARGV[2], 0)
local lat = toNumber(ARGV[3], 0)
local nowTs = toNumber(ARGV[4], 0)
local ttl = toNumber(ARGV[5], 0)
local publishMinInterval = toNumber(ARGV[6], 0)
local publishMinDistance = toNumber(ARGV[7], 0)
local publishEnabled = toNumber(ARGV[8], 1)

redis.call('GEOADD', KEYS[1], ARGV[2], ARGV[3], ARGV[1])
redis.call('SADD', KEYS[2], ARGV[1])
redis.call('SET', KEYS[3], 'online', 'EX', ARGV[5])
redis.call('SET', KEYS[4], cjson.encode({
  lat = ARGV[3],
  lng = ARGV[2],
  ts  = ARGV[4]
}), 'EX', ARGV[5])

local shouldPublish = 0

if publishEnabled == 1 then
  local lastPublish = redis.call('GET', KEYS[5])
  if lastPublish then
    local decoded = cjson.decode(lastPublish)
    local lastLat = toNumber(decoded.lat, lat)
    local lastLng = toNumber(decoded.lng, lng)
    local lastTs = toNumber(decoded.ts, nowTs)
    local elapsed = nowTs - lastTs
    local distance = distanceMeters(lastLat, lastLng, lat, lng)
    if elapsed >= publishMinInterval or distance >= publishMinDistance then
      shouldPublish = 1
    end
  else
    shouldPublish = 1
  end
end

if shouldPublish == 1 then
  redis.call('SET', KEYS[5], cjson.encode({
    lat = ARGV[3],
    lng = ARGV[2],
    ts  = ARGV[4]
  }), 'EX', ARGV[5])
end

return cjson.encode({
  publish = shouldPublish,
  lat = ARGV[3],
  lng = ARGV[2],
  ts = ARGV[4],
  status = 'online'
})
