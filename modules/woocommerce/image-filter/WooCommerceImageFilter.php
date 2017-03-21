<?php
/**
 * This filter makes possible to load remote featured images using a trick.
 * For each product, we set a placeholder as featured img and we save the right url into another post meta.
 * When WordPress tries to load the image, we inject into the process. If the attachment ID is equal to the placeholder,
 * we load the meta value and replace the URL, also correcting the image size returned and the link href.
 *
 * @author Skazza
 */
class WooCommerceImageFilter {
    private static $PLACEHOLDER_ID = 21;

    /**
     * Replace the placeholder URL (if found) with the URL url stored in the _featured_external_url meta.
     * Also corrects dimensions of the returned image.
     *
     * @param array $image Image information from WordPress (image URL, width, height, is icon).
     * @param int $attachmentId ID of the attachment stored into the database.
     * @return array Corrected array with fixed external URL or original one if we're not using the placeholder.
     * @author Skazza
     */
    public function applySrcFilter($image, $attachmentId) {
        /* If it's a placeholder, we need to replace it... */
        if($attachmentId == static::$PLACEHOLDER_ID) {
            global $post;

            $imageInfo = $this->getExternalImage($post->ID);
            if(is_null($imageInfo))
                return $image;

            $image[0] = $imageInfo['url'];
            $image[1] = $imageInfo['width'];
            $image[2] = $imageInfo['height'];
        }

        return $image;
    }

    /**
     * Replaces the href of the img anchor to the correct external URL. This is a WooCommerce filter.
     *
     * @param string $imgHtml The HTML of the image, containing an anchor and the img tag.
     * @return string Corrected HTML.
     * @author Skazza
     */
    public function applyHtmlFilter($imgHtml) {
        $doc = new DOMDocument();
        $doc->loadHTML($imgHtml);
        $imgTag = $doc->getElementsByTagName('img')->item(0);
        $aTag = $doc->getElementsByTagName('a')->item(0);

        if(strpos($aTag->getAttribute('href'), 'external-placeholder') !== false) { /* Do this only if the placeholder is found. */
            $aTag->setAttribute('href', $imgTag->getAttribute('src'));
            return $doc->saveHTML();
        }

        return $imgHtml;
    }

    /**
     * Special filter for cart items, we can't use applySrcFilter because post ID is Cart Page. So we need to
     * hook on a different filter to make it work. This is a WooCommerce filter.
     *
     * @param string $image The HTML of the image, containing the img tag.
     * @param array $product All information of the product.
     * @return string Corrected HTML.
     * @author Skazza
     */
    public function applyCartHtmlFilter($image, $product) {
        $doc = new DOMDocument();
        $doc->loadHTML($image);
        $imgTag = $doc->getElementsByTagName('img')->item(0);

        if(strpos($imgTag->getAttribute('src'), 'external-placeholder') !== false) { /* Do this only if the placeholder is found. */
            $imageInfo = $this->getExternalImage($product['product_id']);
            if(is_null($imageInfo))
                return $image;

            $imgTag->setAttribute('src', $imageInfo['url']);
            $imgTag->setAttribute('width', $imageInfo['width']);
            $imgTag->setAttribute('height', $imageInfo['height']);

            return $doc->saveHTML();
        }

        return $image;
    }

    /**
     * Loads the URL for the external image from the post meta and gets dimensions of the image.
     *
     * @param int $postId The WordPress post ID.
     * @return array|null Array that contains url, width and height. Null if meta is not found.
     * @author Skazza
     */
    private function getExternalImage($postId) {
        $imageInfo = array();
        $imageInfo['url'] = get_post_meta($postId, '_featured_external_url', true);
        if(empty($imageInfo['url']))
            return null;

        $sizes = getimagesize($imageInfo['url']);
        $imageInfo['width'] = $sizes[0];
        $imageInfo['height'] = $sizes[1];

        return $imageInfo;
    }

    /**
     * Returns the attachment ID of the placeholder, used to set it when products are loaded from CSV.
     *
     * @return int The attachment ID of the placeholder.
     * @author Skazza
     */
    public static function getPlaceHolderId() {
        return static::$PLACEHOLDER_ID;
    }
}