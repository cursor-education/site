# -*- mode: ruby -*-
# vi: set ft=ruby :

LOCAL_DOMAINS = %w(local.cursor.education)
VMBOX_MEMORY = 256

Vagrant.configure("2") do |config|
  config.vm.hostname = "vm-cursor-education"

  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true
  config.hostmanager.aliases = LOCAL_DOMAINS

  config.vm.box = "CentOS-6.5-i386-v20140504"
  config.vm.box_url = "https://vagrantcloud.com/nrel/CentOS-6.5-i386/version/3/provider/virtualbox.box"

  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", VMBOX_MEMORY]
  end

  config.vm.network :private_network, ip: "33.33.22.22", auto_config: true

  use_nfs = RUBY_PLATFORM =~ /darwin/ || RUBY_PLATFORM =~ /linux/
  config.vm.synced_folder "./shared/", "/var/shared", id: "vagrant-root", :nfs => use_nfs

  config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

  config.vm.provision :chef_solo do |chef|
    chef.log_level = :info
    chef.cookbooks_path = ["cookbooks"]

    chef.add_recipe "build-essential"
    chef.add_recipe "yum"
    chef.add_recipe "yum-epel"
  end

  config.vm.provision :shell, inline: "echo 'VM booted successfully!'"
end