<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Tomas Kulhanek
 * Email: info@tirus.cz
 */

namespace HelpPC\Serializer\Handler;

use HelpPC\Serializer\Utils\SplFileInfo;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\XmlDeserializationVisitor;
use JMS\Serializer\XmlSerializationVisitor;

class SplFileInfoHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'HelpPC\CzechDataBox\Utils\SplFileInfo',
                'method' => 'serializeSplFileInfoXml',
            ), array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'xml',
                'type' => 'HelpPC\CzechDataBox\Utils\SplFileInfo',
                'method' => 'deserializeXml2SplFileInfo',
            ),
        );
    }

    public function serializeSplFileInfoXml(XmlSerializationVisitor $visitor, SplFileInfo $date, array $type, Context $context)
    {

        return $visitor->visitString(base64_encode($date->getContents()), $type, $context);
    }

    public function deserializeXml2SplFileInfo(XmlDeserializationVisitor $visitor, $date, array $type, Context $context)
    {
        if ((string)$date == null) {
            return null;
        }
        return SplFileInfo::createInTemp(base64_decode((string)$date));
    }
}
