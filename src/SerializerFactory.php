<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Tomas Kulhanek
 * Email: info@tirus.cz
 */

namespace HelpPC\Serializer;


use HelpPC\Serializer\Handler\SplFileInfoHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;

class SerializerFactory
{
    public static function create(){
        $serializer = SerializerBuilder::create();
        $serializer->setPropertyNamingStrategy(
            new SerializedNameAnnotationStrategy(
                new IdenticalPropertyNamingStrategy()
            )
        );
        $serializer->addDefaultHandlers()->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new SplFileInfoHandler());
        });
        return $serializer->build();
    }


}