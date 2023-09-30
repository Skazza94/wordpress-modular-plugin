<?php

namespace WPModular\Filesystem\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\StreamWrapper;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Visibility;

class HttpFilesystemAdapter implements FilesystemAdapter
{
    /** @var  string */
    protected $host;

    /**
     * @var string $path The base path.
     */
    protected $path;

    protected $query;

    /** @var  ClientInterface */
    protected $client;

    /**
     * Constructor
     *
     * @param string $url
     * @param ClientInterface|null $client
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
     * @return ClientInterface
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        return $this->client;
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
     * @param string $path
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
     * Given a file URL it fetches the file metadata
     *
     * @param string $url
     *
     * @return  array
     * @throws  ClientException
     *
     */
    protected function getMetadata($url)
    {
        $visibility = Visibility::PRIVATE;
        $size = 0;
        $timestamp = 0;
        $mimetype = null;

        $client = $this->getClient();
        $response = $client->head($url);
        $status = (int)$response->getStatusCode();

        // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        if ($status == 200 || ($status > 300 && $status <= 308)) {
            $size = (int)$response->getHeader('Content-Length');
            $timestamp = strtotime($response->getHeader('Last-Modified'));
            $mimetype = $response->getHeader('Content-Type');
        }

        if ($status != 401 && $status != 402 && $status != 403) {
            $visibility = Visibility::PUBLIC;
        }

        return compact('visibility', 'size', 'timestamp', 'mimetype');
    }

    public function fileExists(string $path): bool
    {
        $client = $this->getClient();

        try {
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

    public function directoryExists(string $path): bool
    {
        return $this->fileExists($path);
    }

    public function write(string $path, string $contents, Config $config): void
    {
        return;
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        return;
    }

    public function read(string $path): string
    {
        $client = $this->getClient();
        $response = $client->get($this->buildUrl($path));

        if (200 !== ((int)$response->getStatusCode())) {
            return false;
        }

        return (string)$response->getBody();
    }

    public function readStream(string $path)
    {
        $client = $this->getClient();
        $response = $client->head($this->buildUrl($path));
        $streamObj = $response->getBody();

        return StreamWrapper::getResource($streamObj);
    }

    public function delete(string $path): void
    {
        return;
    }

    public function deleteDirectory(string $path): void
    {
        return;
    }

    public function createDirectory(string $path, Config $config): void
    {
        return;
    }

    public function setVisibility(string $path, string $visibility): void
    {
        return;
    }

    public function visibility(string $path): FileAttributes
    {
        $metadata = $this->getMetadata($path);

        return new FileAttributes($path, null, $metadata['visibility'], null, null);
    }

    public function mimeType(string $path): FileAttributes
    {
        $metadata = $this->getMetadata($path);

        return new FileAttributes($path, null, null, null, $metadata['mimetype']);
    }

    public function lastModified(string $path): FileAttributes
    {
        $metadata = $this->getMetadata($path);

        return new FileAttributes($path, null, null, $metadata['timestamp'], null);
    }

    public function fileSize(string $path): FileAttributes
    {
        $metadata = $this->getMetadata($path);

        return new FileAttributes($path, $metadata['size'], null, null, null);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        return null;
    }

    public function move(string $source, string $destination, Config $config): void
    {
        return;
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        return;
    }
}
