Exec {
    path => ["/bin", "/sbin", "/usr/bin", "/usr/sbin"],
}

include apt
include apache

a2mod { "Enable rewrite mod":
    name => "rewrite",
    ensure => "present"
}

apache::vhost { 'gitelephant_host.vagrant':
    priority        => '10',
    vhost_name      => 'gitelephant_host.vagrant',
    port            => '80',
    docroot         => '/vagrant/web',
    #logroot         => '/srv/www.example.com/logroot/',
    serveradmin     => 'matteog@gmail.com'
}

package { ["php5", "php-apc"]:
    ensure => installed
}

#exec { "assets:install":
#    command => "/usr/bin/php /vagrant/app/console assets:install web",
#    path    => "/vagrant/"
#}