<?php
namespace App\Interface;

class JsonWebTokenInterface
{
	public $iss;
	public $iat;
	public $nbf;
	public $exp;
	public $data;
	
	public function __construct(array $payload)
	{
		$this->iss = $payload['iss'];
		$this->iat = $payload['iat'];
		$this->nbf = $payload['nbf'];
		$this->exp = $payload['exp'];
		$this->data = $payload['data'];
	}
}