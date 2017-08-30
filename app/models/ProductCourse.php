<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ProductCourse extends BaseModel
{

    // ------------------------------------------------------------------------------

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        /** DB Table Name */
        $this->setSource('product_course');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->belongsTo("product_id", "Product", "id");
        $this->hasMany("id", "UserAction", "product_course_id");
        $this->hasMany("id", "ProductCourseMeta", "product_course_id");
        $this->hasMany("id", "ProductCourseSection", "product_id");
    }

    // ------------------------------------------------------------------------------

    /**
     * Gets the Previous Course in a Product Series
     *
     * @param  int    $product_id
     * @param  int    $section
     * @param  int    $course
     *
     * @return obj|int
     */
    public function getPrevCourse(int $product_id, int $section, int $course)
    {
        return $this->_getSingleCourse('prev', $product_id, $section, $course);
    }

    // -----------------------------------------------------------------------------

    /**
     * Gets the Next Course in a Product Series
     *
     * @param  int    $product_id
     * @param  int    $section
     * @param  int    $course
     *
     * @return obj|int
     */
    public function getNextCourse(int $product_id, int $section, int $course)
    {
        return $this->_getSingleCourse('next', $product_id, $section, $course);
    }

    // -----------------------------------------------------------------------------

    /**
     * Create a RTMP Signed URL
     *
     * @param  string   $productPath S3 Folder   $productPath
     * @param  string    $courseName  S3 Filename $courseName
     *
     * @return array
     */
    public static function generateStreamUrl(string $productPath, string $courseName)
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

        $di = \Phalcon\Di::getDefault();
        $api = $di->get('api');

        $signedUrl = [];
        foreach ($resourceUris as $key => $value) {
            // Note: I can change expires to policy and limit to an IP
            // But I had trouble getting it running, see: http://docs.aws.amazon.com/aws-sdk-php/guide/latest/service-cloudfront.html
            $signedUrl[$key] = $cloudfront->getSignedUrl([
                'url'         => getenv('AWS_CLOUDFRONT_RMTP_URL') . $value,
                'expires'     => $api->aws->cloudfront->expiration,
                'private_key' => $api->aws->cloudfront->privateKeyLocation,
                'key_pair_id' => getenv('AWS_CLOUDFRONT_KEYPAIR_ID')
            ]);
        }

        return $signedUrl;
    }

    // -----------------------------------------------------------------------------

    /**
     * Gets the Next or Prev Course in a Product Series
     *
     * @param  string $nextOrPrev Only accepts 'next' or 'prev'
     * @param  int    $product_id
     * @param  int    $section
     * @param  int    $course
     *
     * @return obj|int
     */
    private function _getSingleCourse(string $nextOrPrev = 'next', int $product_id, int $section, int $course)
    {

        $nextOrPrev = strtolower($nextOrPrev);

        if (!in_array($nextOrPrev, ['next', 'prev'])) {
            throw new \InvalidArgumentException('ProductCourse Model must supply string: next or prev only.');
        }

        if ($nextOrPrev === 'next') {
            $try_section += 1;
            $try_course += 1;
            $order_mode = 'ASC'; // lowest ID first [Going Forwards]
        } elseif ($nextOrPrev === 'prev') {
            $try_section -= 1;
            $try_course -= 1;
            $order_mode = 'DESC'; //highest ID first [Going Backwards]
        }

        // [1] First see if a course is next within this section
        $result = $this->findFirst([
            'product_id = :product_id:
            AND section = :section:
            AND course = :course:
            ORDER BY course :order_mode:
            LIMIT 1
            ',
            'bind'    => [
                'product_id' => (int) $product_id,
                'section' => (int) $section,
                'course' => (int) $try_course,
                'order_mode' => $order_mode
            ]
        ]);

        if ($result->count()) {
            return $result;
        }

        // [2] Otherwise check a different Section with
        $result = $this->findFirst([
            'product_id = :product_id:
            AND section = :section:
            ORDER BY course :order_mode:
            LIMIT 1
            ',
            'bind'    => [
                'product_id' => (int) $product_id,
                'section' => (int) $try_section,
                'order_mode' => $order_mode
            ]
        ]);

        if ($result->count()) {
            return $result;
        }

        // [3] No Results, we are either at beginning or end of entire series.
        return false;
    }

    // -----------------------------------------------------------------------------

}
