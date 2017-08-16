<?php

namespace Component;

/**
 * Helper Utilities
 *
 * Phalcon\Mvc\User\Component extends abstract class Phalcon\Di\Injectable
 */
class Helper extends \Phalcon\Mvc\User\Component
{

    // Get the Exact Timestamp with the Applied Timezone
    public function getLocaleTimestamp($timezone = 'America/New_York')
    {
        $date = new DateTime(time(), new DateTimeZone($timezone));

        return $date->getTimestamp();
    }

    /**
     * Display stars for the product difficulty
     *
     * @param  intger $num
     *
     * @return string (HTML)
     */
    public function productDifficulty($num)
    {
        $empty_stars = 5 - $num;
        $full_stars = 5 - $empty_stars;

        $output = '';

        for ($i = 0; $i < $full_stars; $i++) {
            $output .= '<i class="fa fa-circle star-rating"></i> ';
        }

        for ($i = 0; $i < $empty_stars; $i++) {
            $output .= '<i class="fa fa-circle-o star-rating-disabled"></i> ';
        }

        return $output;
    }

    // --------------------------------------------------------------


    /**
     * Validate CSRF Tokens
     *
     * @param  boolean|string $redirectOnFailure (Optional) redirection
     *
     * @return boolean
     */
    public function csrf($redirectOnFailure = false, $isAjax = false)
    {
        if ($this->security->checkToken() == false)
        {
            // Only show a flash if its not an ajax call, otherwise use the boolean result.
            if (!$isAjax)
            {
                $this->flash->error('Invalid CSRF Token.');

                // Only redirect when supplied
                if ($redirectOnFailure)
                {
                    header('location: ' . getBaseUrl($redirectOnFailure));
                    exit;
                }
            }

            // Very Important for AJAX calls
            return false;
        }

        // Very Important for AJAX calls
        return true;
    }

    // --------------------------------------------------------------

    public function getMetaIcon($type)
    {
        switch ($type) {
            case 'text':
                return '<span class="glyphicon glyphicon-align-justify"></span>';
                break;
            case 'file':
                return '<span class="glyphicon glyphicon-download-alt"></span>';
                break;
            case 'link':
                return '<span class="glyphicon glyphicon-link"></span>';
                break;
        }
    }

    // --------------------------------------------------------------

    /**
     * Create a RTMP Signed URL
     *
     * @param  S3 Folder   $productPath
     * @param  S3 Filename $courseName
     *
     * @return string
     */
    public function generateStreamUrl($productPath, $courseName)
    {
        // ----------------------------
        // Load the AWS Config
        // * - key_pair_id: The ID of the key pair used to sign CloudFront URLs for private distributions.
        // * - private_key: The filepath ot the private key used to sign CloudFront URLs for private distributions.
        // ----------------------------
        $cloudfront = new \Aws\CloudFront\CloudFrontClient([
            'region'  => getenv('AWS_CLOUDFRONT_REGION'),
            'version' => getenv('AWS_CLOUDFRONT_VERSION')
        ]);

        $resourceUris = [
            'mp4' => sprintf('%s/%s.mp4', $productPath, $courseName),
            // 'webm'    => sprintf('%s/webmhd/%s.webmhd.webm', $productPath, $courseName)
        ];

        $signedUrl = [];
        foreach ($resourceUris as $key => $value) {
            // Note: I can change expires to policy and limit to an IP
            // But I had trouble getting it running, see: http://docs.aws.amazon.com/aws-sdk-php/guide/latest/service-cloudfront.html
            $signedUrl[$key] = $cloudfront->getSignedUrl([
                'url'         => getenv('AWS_CLOUDFRONT_RMTP_URL') . $value,
                'expires'     => $this->api->aws->cloudfront->expiration,
                'private_key' => $this->api->aws->cloudfront->privateKeyLocation,
                'key_pair_id' => getenv('AWS_CLOUDFRONT_KEYPAIR_ID')
            ]);
        }

        return $signedUrl;
    }

    // --------------------------------------------------------------

}
// End of File
// --------------------------------------------------------------
