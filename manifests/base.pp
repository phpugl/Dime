class phpugl {
  exec { 'apt-get update': command => '/usr/bin/apt-get update' }
  package { "git-core": ensure => present }
  package { "php5-cli": ensure => present }
  package { "php5-cgi": ensure => present }
  package { "php5-intl": ensure => present }
  package { "php5-mysql": ensure => present }
  package { "php-apc": ensure => present }
  package { "spawn-fcgi": ensure => present }
  package { "nginx": ensure => present }
  package { "php-pear": ensure => present }
  package { "mysql-server": ensure => present }
  package { "mysql-client": ensure => present }

  file { "/vagrant/app/cache": ensure => directory, mode => 766 }
  file { "/vagrant/app/logs": ensure => directory, mode => 766 }

  file { "/vagrant/app/config/parameters.yml": 
    ensure => file,
    replace => false,
    source => "/vagrant/app/config/parameters.yml.template" 
  }

  exec { "/vagrant/bin/vendors install":
    require => [Package["php5-cli"], Package["git-core"]],
    creates => "/vagrant/vendor/symfony"
  }

  service { "nginx": ensure => running }

  file { "/etc/nginx/sites-enabled/default": ensure => absent }
  file { "/etc/nginx/sites-available/phpugl.local":
    ensure => link, 
    notify => Service["nginx"],
    target => "/vagrant/manifests/files/phpugl.local"
  }
  file { "/etc/nginx/sites-enabled/phpugl.local":
    ensure => link, 
    target => "/etc/nginx/sites-available/phpugl.local"
  }

  file { "/etc/init.d/php-cgi": source => "/vagrant/manifests/files/php-cgi.init", mode => 744 }
  file { "/etc/default/php-cgi": source => "/vagrant/manifests/files/php-cgi" }

  service { "php-cgi": 
    ensure => running,
    require => File["/etc/init.d/php-cgi"]
  }

  file { "/etc/php5/cgi/php.ini": 
    source => "/vagrant/manifests/files/php.cgi.ini",
    notify => Service["php-cgi"]
  }
}

include phpugl
