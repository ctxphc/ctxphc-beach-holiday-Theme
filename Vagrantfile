# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "deviantintegral/trusty64-lamp"
  config.vm.provider "vmware_workstation"
  config.vm.hostname = "ctxphc.com"
  config.vm.network "public_network", bridge: "Intel(R) 82567LM-3 Gigabit Network Connection", ip: "192.168.1.76"
  # config.vm.synced_folder "src/", "/var/www/ctxphc.com/public_html"
  config.vm.synced_folder "wp-latest", "/var/www/ctxphc.com/public_html/"
  config.vm.synced_folder "themes", "/var/www/ctxphc.com/public_html/wp-content/themes/"
  config.vm.synced_folder "plugins", "/var/www/ctxphc.com/public_html/wp-content/plugins/"
end
