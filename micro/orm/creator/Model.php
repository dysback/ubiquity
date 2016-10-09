<?php
namespace micro\orm\creator;
class Model {
	private $members;
	private $name;

	public function __construct($name){
		$this->name=\ucfirst($name);
		$this->members=array();
	}

	public function addMember($member){
		$this->members[$member->getName()]=$member;
		return $this;
	}

	public function addManyToOne($member,$name,$className,$nullable=false){
		if(\array_key_exists($member, $this->members)===false){
			$this->addMember(new Member($member));
		}
		$this->members[$member]->addManyToOne($name,$className,$nullable);
	}

	public function addOneToMany($member,$mappedBy,$className){
		if(\array_key_exists($member, $this->members)===false){
			$this->addMember(new Member($member));
		}
		$this->members[$member]->addOneToMany($mappedBy,$className);
	}
	public function __toString(){
		$result="<?php\nclass ".ucfirst($this->name)."{";
		$members=$this->members;
		\array_walk($members,function($item){return $item."";});
		$result.=implode("", $members);
		$result.="\n}";
		return $result;
	}

	public function getName() {
		return $this->name;
	}

}