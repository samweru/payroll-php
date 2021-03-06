<?php

namespace App\Provider;

use Strukt\Contract\AbstractProvider;
use Strukt\Contract\ProviderInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use App\Service\Normalizer\DateTime as DateTimeNormalizer;

class Normalizer extends AbstractProvider implements ProviderInterface{

	public function __construct(){

		//
	}

	public function register(){

		$this->core()->set("app.service.normalizer", new class{

			public function toArray($obj){

				$objNorm = new ObjectNormalizer();

				$dateNorm = new DateTimeNormalizer();

				$serializer = new Serializer(array($dateNorm, $objNorm));

				return $serializer->normalize($obj, null, array('ignored_attributes' => array(

					'__initializer__',
					'__isInitialized__', 
					'__cloner__'
				)));
			}

			public function toJson($obj){

				$methNorm = new GetSetMethodNormalizer();

				$dateNorm = new DateTimeNormalizer();

				$serializer = new Serializer(array(

					$dateNorm, 
					$methNorm
				), 
				array(

					'json' => new JsonEncoder()
				));

				return $serializer->serialize($obj, 'json');
			}
		});	
	}
}

