<?php
/**
 * Created by PhpStorm.
 * User: arnold
 * Date: 2018/4/18
 * Time: 下午2:32
 */

interface Command{
    function execute();
}
//具体命令角色AttackCommand,指定接受者执行攻击命令
class AttackCommand implements Command
{
    private $receiver;
    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;
    }
    public function execute()
    {
        // TODO: Implement execute() method.
        $this->receiver->acctackAction();
    }
}
//具体命令角色DefenseCommand,指定接受者执行防御命令
class DefenseCommand implements Command
{
    private $reciver;
    public function __construct(Receiver $receiver)
    {
        $this->reciver = $receiver;
    }
    public function execute()
    {
        // TODO: Implement execute() method.
        $this->reciver->defenseAction();
    }
}
//接受者，执行具体命令角色的命令
class Receiver{
    private $name;
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function acctackAction()
    {
        echo $this->name."执行了攻击命令\n";
    }
    public function defenseAction(){
        echo $this->name."执行了防御命令\n";
    }
}

//请求者，请求具体命令的执行
class Invoker
{
    private $concreteCommand;
    public function __construct($concreteCommand)
    {
        $this->concreteCommand = $concreteCommand;
    }
    public function executeCommand(){
        $this->concreteCommand->execute();
    }
}


class Client
{
    public function __construct()
    {
        $zhao = new Receiver("叶良辰");
        $attackCommand = new AttackCommand($zhao);
        $attackInvoker = new Invoker($attackCommand);
        $attackInvoker->executeCommand();
    }
}

$obj = new Client();