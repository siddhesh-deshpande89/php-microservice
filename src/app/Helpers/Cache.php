<?php
namespace App\Helpers;

class Cache
{

    /**
     * Updates cache by key
     *
     * @param string $key
     * @param array $data
     * @return bool
     */
    public static function update(string $key, array $data): bool
    {
        if (empty($data)) {
            return true;
        }

        // TODO move to redis
        try {
            $filePath = self::getCacheFilePath($key);

            if (file_exists($filePath)) {

                return self::appendCache($filePath, $data);
            }

            $data = self::checkArrayHasChildArray($data) ? $data : [
                $data
            ];

            return self::writeCache($filePath, json_encode($data));
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * Checks if element of array is another array
     * 
     * @param array $data
     * @return bool
     */
    public function checkArrayHasChildArray(array $data): bool
    {
        foreach ($data as $value) {
            if (is_Array($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get cache data by key
     *
     * @param string $key
     * @return array
     */
    public static function get(string $key): array
    {
        $filePath = self::getCacheFilePath($key);

        if (file_exists($filePath)) {

            $data = file_get_contents($filePath);

            return self::decodeCache($data, true);
        }

        return [];
    }

    /**
     * Gets cache file path
     *
     * @param string $fileName
     * @return string
     */
    public function getCacheFilePath(string $fileName): string
    {
        return dirname(__DIR__) . '/../storage/cache/' . $fileName;
    }

    /**
     * Writes data to cache file
     *
     * @param string $filePath
     * @param string $data
     */
    protected function writeCache(string $filePath, string $data): bool
    {
        try {

            file_put_contents($filePath, $data);

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * Appends data to cache file
     *
     * @param string $filePath
     * @param array $data
     */
    protected function appendCache(string $filePath, array $data): bool
    {
        $cacheContent = file_get_contents($filePath);

        $appendedData = self::getAppendedData($cacheContent, $data);

        return self::writeCache($filePath, json_encode($appendedData));
    }

    /**
     * Decodes cache string data
     *
     * @param string $cacheData
     * @return array
     */
    protected function decodeCache(string $cacheData): array
    {
        return json_decode($cacheData, true);
    }

    /**
     * Appends new cache data array to existing array
     *
     * @param string $cacheContent
     * @param array $newData
     * @return array
     */
    protected function getAppendedData(string $cacheContent, array $newData): array
    {
        $oldData = self::decodeCache($cacheContent, true);

        $oldData[] = $newData;

        return $oldData;
    }
}