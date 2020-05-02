<?php

namespace App\Services;

use App\CommandLink;
use App\User;
use App\OnlineApplication;
use Http;

class CommandLinkService
{
  public function getByName($name)
  {
    return CommandLink::where('name', '=', $name)->first();
  }

  public function executeCommand($commandName, $input, $authToken){
    $command = $this->getByName($commandName);
    
    $commandType = $command->getLinkType();

    return $this->{$commandType}($command, $input, $authToken);
  }

  public function get($commandToExecute, $input, $authToken){
    $link = $commandToExecute->link;
    $output = $this->getContents($link, '{', '}');
    
    return $output;

    return Http::withToken($authToken)->get($commandToExecute->link)->json();
  }
  public function send($commandToExecute, $input){

  }

  function getContents($str, $startDelimiter, $endDelimiter) {
    $contents = array();
    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = 0;
    while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
      $contentStart += $startDelimiterLength;
      $contentEnd = strpos($str, $endDelimiter, $contentStart);
      if (false === $contentEnd) {
        break;
      }
      //Do this so we also get the delimiters
      $contents[] = substr($str, $contentStart - 1, $contentEnd - $contentStart + 2);
      $startFrom = $contentEnd + $endDelimiterLength;
    }
  
    return $contents;
  }
}
