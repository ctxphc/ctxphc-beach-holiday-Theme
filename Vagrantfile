# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "deviantintegral/trusty64-lamp"
  config.vm.provider "vmware_workstation"
  config.vm.hostname = "ctxphc.com"
  config.vm.network "public_network", bridge: "Intel(R) 82567LM-3 Gigabit Network Connection", ip: "192.168.1.76"
  config.vm.synced_folder ".", "/var/www/ctxphc.com/public_html/"

  config.vm.provider "vmware_workstation" do |vmware|
    vmware.vmx["memsize"] = "2048"
  end
end
