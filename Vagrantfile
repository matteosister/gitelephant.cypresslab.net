# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
    #config.vm.box = "precise64"
    #config.vm.box_url = "http://files.vagrantup.com/precise64.box"
    config.vm.box = "quantal64"
    config.vm.box_url = "https://github.com/downloads/roderik/VagrantQuantal64Box/quantal64.box"

    config.vm.network :hostonly, "33.33.33.33"

    #config.vm.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    #config.vm.customize do |vm|
    #    vm.memory_size = 256
    #end

    config.vm.share_folder("main", "/var/www/gitelephant_host.lo", ".", :nfs => true)
    #config.vm.synced_folder ".", "/var/www/gitelephant_host.lo", :nfs => true

    config.vm.provision :chef_solo do |chef|
        chef.cookbooks_path = ["cookbooks", "vagrant/vagrant-symfony2/my-cookbooks", "vagrant/mdxp"]
        chef.add_recipe "nginx"
        chef.add_recipe "apt"
        chef.add_recipe "build-essential"
        chef.add_recipe "php-fpm"
        chef.add_recipe "nginx"
        chef.add_recipe "php"
        chef.add_recipe "php::module_mysql"
        chef.add_recipe "php::module_apc"
        chef.add_recipe "composer"
        chef.add_recipe "mysql"
        chef.add_recipe "mysql::server"
        chef.add_recipe "git"
        chef.add_recipe "viagrant"
        chef.add_recipe "nodejs"
        chef.json = {
            app: {
                name: "gitelephant_host",
                web_dir: "/var/www/gitelephant_host.lo",
                dev: true
            },
            nginx: {
                user: "vagrant"
            },
            mysql: {
                server_root_password: "",
                server_repl_password: "",
                server_debian_password: "gitelephant_host"
            },
            nodejs: {
                version: "0.10.0"
            }
        }
    end
end
