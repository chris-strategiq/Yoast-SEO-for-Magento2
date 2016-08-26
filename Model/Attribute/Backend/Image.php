<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0).
 * This license is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.en.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category    Maxserv: MaxServ_YoastSeo
 * @package     Maxserv: MaxServ_YoastSeo
 * @author      Vincent Hornikx <vincent.hornikx@maxser.com>
 * @copyright   Copyright (c) 2016 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Model\Attribute\Backend;

use Magento\Catalog\Model\ImageUploader;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\DataObject;

class Image extends AbstractBackend
{

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    public function __construct(
        ImageUploader $imageUploader
    ) {
        $this->imageUploader = $imageUploader;
    }

    /**
     * @param DataObject $object
     * @return $this
     */
    public function beforeSave($object)
    {
        $this->updateImage($object, 'facebook');
        $this->updateImage($object, 'twitter');

        return $this;
    }

    /**
     * @param DataObject $object
     * @param string $type
     */
    protected function updateImage($object, $type)
    {
        $image = $object->getData('yoast_' . $type . '_image', null);

        if (is_array($image)) {
            if (isset($image[0]['name'])) {
                $image = $image[0]['name'];
            } else {
                $image = null;
            }
            $object->setData('yoast_' . $type . '_image', $image);
        }

        if ($image !== null) {
            try {
                $this->imageUploader->moveFileFromTmp($image);
            } catch (\Exception $e) {
                //$this->_logger->critical($e);
            }
        }
    }
}
