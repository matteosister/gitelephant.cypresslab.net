# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
    config.vm.box = "precise64"
    config.vm.box_url = "http://files.vagrantup.com/precise64.box"
    config.vm.forward_port 80, 8000
    config.vm.provision :puppet do |puppet|
        puppet.manifests_path = "app/puppet/manifests"
        puppet.manifest_file  = "default.pp"
        puppet.module_path = "app/puppet/modules"
    end
end
