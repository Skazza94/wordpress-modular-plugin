<?php

namespace WPModular\Filesystem\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\StreamWrapper;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use League\Flysystem\AdapterInterface;

class Http extends AbstractAdapter
{
    /** @var  string  */
    protected $host;

    /**
     * @var string $path The base path.
     */
    protected $path;

    protected $query;

    /** @var  ClientInterface  */
    protected $client;

    /**
     * Constructor
     *
     * @param  string           $url
     * @param  ClientInterface  $client
     */
    public function __construct($url, ClientInterface $client = null)
    {
        $parsedUrl = parse_url($url);
        $this->host = $parsedUrl['scheme'] . '://';
        if (isset($parsedUrl['user']) && isset($parsedUrl['pass'])) {
            $this->host .= $parsedUrl['user'] . ':' . $parsedUrl['pass'] . '@';
        };
        $this->host .= $parsedUrl['host'];
        $this->host .= (array_key_exists('port', $parsedUrl)) ? ':' . $parsedUrl['port'] : '';

        $this->path = $parsedUrl['path'];

        $this->query = (array_key_exists('query', $parsedUrl)) ? $parsedUrl['query'] : null;

        if (null !== $client) {
            $this->setClient($client);
        }
    }

    /**
     * Check whether a file is present
     *
     * @param   string  $path
     *
     * @return  boolean
     */
    public function has($path)
    {
        $client = $this->getClient();

        try {
            /** @var ResponseInterface $response */
            $response = $client->head($this->buildUrl($path));
        } catch (ClientException $e) {
            return false;
        }
        $code = $response->getStatusCode();

        if ($code != 200) {
            return false;
        }

        return true;
    }

    /**
     * Write a file
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param  string  $path
     * @param  string  $contents
     * @param  Config  $config
     *
     * @return  array|bool
     */
    public function write($path, $contents, Config $config)
    {
        return false;
    }

    /**
     * Write using a stream
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param  string  $path
     * @param  string  $resource
     * @param  Config  $config
     *
     * @return array|bool
     */
    public function writeStream($path, $resource, Config $config)
    {
        return false;
    }

    /**
     * Get a read-stream for a file
     *
     * @param  string $path
     *
     * @return  array|bool
     */
    public function readStream($path)
    {
        $client = $this->getClient();
        $response = $client->head($this->buildUrl($path));
        $streamObj = $response->getBody();
        $stream = StreamWrapper::getResource($streamObj);

        return compact('stream', 'path');
    }

    /**
     * Update a file using a stream
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param  string    $path
     * @param  resource  $resource
     * @param  Config    $config
     *
     * @return  array|bool
     */
    public function updateStream($path, $resource, Config $config)
    {
        return false;
    }

    /**
     * Update a file
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param  string  $path
     * @param  string  $contents
     * @param  Config  $config
     *
     * @return  array|bool
     */
    public function update($path, $contents, Config $config)
    {
        return false;
    }

    /**
     * Read a file
     *
     * @param   string  $path
     *
     * @return  array|bool
     */
    public function read($path)
    {
        $client = $this->getClient();
        /** @var ResponseInterface $response */
        $response = $client->get($this->buildUrl($path));

        if (200 !== ((int) $response->getStatusCode())) {
            return false;
        }

        $contents = (string) $response->getBody();

        return compact('contents', 'path');
    }

    /**
     * Rename a file
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param   string $path
     * @param   string $newpath
     *
     * @return  bool
     */
    public function rename($path, $newpath)
    {
        return false;
    }

    /**
     * Copy a file
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param   string $path
     * @param   string $newpath
     *
     * @return  bool
     */
    public function copy($path, $newpath)
    {
        return false;
    }

    /**
     * Delete a file
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param   string $path
     *
     * @return  bool
     */
    public function delete($path)
    {
        return false;
    }

    /**
     * List contents of a directory
     * There are no directories in HTTP, so this always returns an empty array
     *
     * @param  string  $directory
     * @param  bool    $recursive
     *
     * @return false|array
     */
    public function listContents($directory = '', $recursive = false)
    {
        return false;
    }

    /**
     * Get the metadata of a file
     *
     * @param   string $path
     *
     * @return  array
     */
    public function getMetadata($path)
    {
        $metadata = $this->fetchRemoteFileMetadata($this->buildUrl($path));

        return $this->normalizeFileInfo($path, $metadata);
    }

    /**
     * Get the size of a file
     *
     * @param   string $path
     *
     * @return  array
     */
    public function getSize($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * Get the mimetype of a file
     *
     * @param   string $path
     *
     * @return  array
     */
    public function getMimetype($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * Get the timestamp of a file
     *
     * @param   string $path
     *
     * @return  array
     */
    public function getTimestamp($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * Get the visibility of a file
     *
     * @param   string $path
     *
     * @return  mixed
     */
    public function getVisibility($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     *
     * @return array|false
     */
    public function createDir($dirname, Config $config)
    {
        return false;
    }

    /**
     * Delete a directory
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param   string $path
     *
     * @return  bool
     */
    public function deleteDir($path)
    {
        return false;
    }

    /**
     * Set the visibility for a file.
     *
     * It's not possible to write to HTTP so it will always do nothing and return false
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility)
    {
        return false;
    }

    /**
     * Normalize the file info
     *
     * @param  string  $path
     * @param  array   $fileMetadata
     *
     * @return  array
     */
    protected function normalizeFileInfo($path, array $fileMetadata)
    {
        $normalized = array(
            'type'       => 'file',
            'path'       => $path,
            'timestamp'  => $fileMetadata['timestamp'],
            'size'       => $fileMetadata['size'],
            'visibility' => $fileMetadata['visibility']
        );

        return $normalized;
    }

    /**
     * Given a file URL it fetches the file metadata
     *
     * @param   string $url
     *
     * @throws  ClientException
     *
     * @return  array
     */
    protected function fetchRemoteFileMetadata($url)
    {
        $client = $this->getClient();
        /** @var ResponseInterface $response */
        $response = $client->head($url);
        $status = (int) $response->getStatusCode();
        $visibility = AdapterInterface::VISIBILITY_PRIVATE;

        // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        if ($status == 200 || ($status > 300 && $status <= 308)) {
            $size = (int) $response->getHeader('Content-Length');
            $timestamp = strtotime($response->getHeader('Last-Modified'));
            $mimetype = $response->getHeader('Content-Type');
        }

        if ($status != 401 && $status != 402 && $status != 403) {
            $visibility = AdapterInterface::VISIBILITY_PUBLIC;
        }

        return compact('visibility', 'size', 'timestamp', 'mimetype');
    }

    /**
     * @param   string $path
     *
     * @return  string
     */
    protected function buildUrl($path)
    {
        $finalPath = (is_null($path) || empty($path)) ? '' : '/' . trim($path, '/');
        $query = (!is_null($this->query)) ? "?{$this->query}" : '';
        return $this->host . rtrim($this->path, '/') . $finalPath . $query;
    }

    /**
     * @param ClientInterface $client
     *
     * @return self
     */
    protected function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        if ( ! $this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }
}
