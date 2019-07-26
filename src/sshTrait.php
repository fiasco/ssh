<?php
namespace Fiasco\Ssh;

trait sshTrait {
  protected $cipher, $sshConfig, $identityFile, $limit, $sshOptions = [], $port;

  public function setCipher($cipher)
  {
    $this->cipher = $cipher;
    return $this;
  }

  public function setSshConfig($config)
  {
    $this->sshConfig = $config;
    return $this;
  }

  public function setIdentityFile($file)
  {
    $this->identityFile = $file;
    return $this;
  }

  public function setLimit($limit)
  {
    $this->limit = $limit;
    return $this;
  }

  public function addOption($key, $value)
  {
    $this->sshOptions[$key] = $value;
    return $this;
  }

  public function setPort($port)
  {
    $this->port = $port;
    return $this;
  }

  protected function prepareCommand($bin = 'ssh')
  {
    $command[] = $bin;
    if (!empty($this->cipher)) {
      $command[] = '-c';
      $command[] = $this->cipher;
    }

    if (!empty($this->sshConfig)) {
      $command[] = '-F';
      $command[] = $this->sshConfig;
    }

    if (!empty($this->identityFile)) {
      $command[] = '-i';
      $command[] = $this->identityFile;
    }

    if (!empty($this->limit)) {
      $command[] = '-l';
      $command[] = $this->limit;
    }

    foreach ($this->sshOptions as $key => $value) {
      $command[] = '-o';
      $command[] = "$key=$value";
    }

    return $command;
  }
}
 ?>
