<?php

namespace micro\utils\yuml;

use micro\cache\CacheManager;
use micro\controllers\Startup;
use micro\cache\ClassUtils;
use micro\utils\StrUtils;

class ClassesToYuml {
	private $displayProperties;
	private $displayAssociations;
	private $displayMethods;
	private $displayMethodsParams;
	private $displayPropertiesTypes;

	public function __construct($displayProperties=true,$displayAssociations=true,$displayMethods=false,$displayMethodsParams=false,$displayPropertiesTypes=false){
		$this->displayProperties=$displayProperties;
		$this->displayAssociations=$displayAssociations;
		$this->displayMethods=$displayMethods;
		$this->displayMethodsParams=$displayMethodsParams;
		$this->displayPropertiesTypes=$displayPropertiesTypes;
	}
	/**
	 * @param boolean $displayProperties
	 * @param boolean $displayAssociations
	 * @param boolean $displayMethods
	 * @param boolean $displayMethodsParams
	 * @param boolean $displayPropertiesTypes
	 * @return ClassParser[]|string[]
	 */
	public function parse(){
		$yumlResult=[];
		$config=Startup::getConfig();
		$files=CacheManager::getModelsFiles($config,true);
		if(\sizeof($files)!==0){
			foreach ($files as $file){
				$completeName=ClassUtils::getClassFullNameFromFile($file);
				$yumlR=new ClassToYuml($completeName,$this->displayProperties,false,$this->displayMethods,$this->displayMethodsParams,$this->displayPropertiesTypes,false);
				$yumlResult[]=$yumlR;
			}
			if($this->displayAssociations){
				$count=\sizeof($files);
				for($i=0;$i<$count;$i++){
					$result=$yumlResult[$i]->oneToManyTostring();
					if(StrUtils::isNotNull($result))
						$yumlResult[]=$result;
				}
			}
		}
		return $yumlResult;
	}

	public function __toString(){
		return \implode(Yuml::$groupeSeparator,$this->parse());
	}
}
