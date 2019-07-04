# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'yaml'
require 'fileutils'

required_plugins = %w( vagrant-hostmanager vagrant-vbguest vagrant-bindfs )
required_plugins.each do |plugin|
    exec "vagrant plugin install #{plugin}" unless Vagrant.has_plugin? plugin
end

domains = {
  frontend: 'muzone.local',
  backend: 'adm.muzone.local',
  tg: 'tg.muzone.local'
}

config = {
  local: './vagrant/config/vagrant-local.yml',
  example: './vagrant/config/vagrant-local.example.yml'
}

# copy config from example if local config not exists
FileUtils.cp config[:example], config[:local] unless File.exist?(config[:local])
# read config
options = YAML.load_file config[:local]

# check github token
if options['github_token'].nil? || options['github_token'].to_s.length != 40
  puts "You must place REAL GitHub token into configuration:\n/yii2-app-advanced/vagrant/config/vagrant-local.yml"
  exit
end

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  config.vm.box = 'centos/7'

  config.vm.box_check_update = options['box_check_update']

  config.vm.provider 'libvirt' do |libvirt|
    config.bindfs.install_bindfs_from_source = true
    config.bindfs.default_options = {
      force_user:   'vagrant',
      force_group:  'vagrant',
      perms:        'u=rwX:g=rwXD:o=rwXD'
    }

    # sync: folder 'poster' (host machine) -> folder '/app' (guest machine)
    config.vm.synced_folder './', '/vagrant-nfs', type: 'nfs',  nfs_udp: false, nfs_version: 4, linux__nfs_options: ['rw','no_subtree_check','all_squash','async']
  end

  config.bindfs.bind_folder "/vagrant-nfs", "/app"

  # machine name (for vagrant console)
  config.vm.define options['machine_name']

  # machine name (for guest machine console)
  config.vm.hostname = options['machine_name']

  # network settings
  config.vm.network 'private_network', ip: options['ip']

  # disable folder '/vagrant' (guest machine)
  config.vm.synced_folder '.', '/vagrant', disabled: true
  config.vm.synced_folder '.', '/backend/runtime', disabled: true
  config.vm.synced_folder '.', '/frontend/runtime', disabled: true
  config.vm.synced_folder '.', '/tg/runtime', disabled: true

  # hosts settings (host machine)
  config.vm.provision :hostmanager
  config.hostmanager.enabled            = true
  config.hostmanager.manage_host        = true
  config.hostmanager.ignore_private_ip  = false
  config.hostmanager.include_offline    = true
  config.hostmanager.aliases            = domains.values

  # provisioners
  config.vm.provision 'shell', path: './vagrant/provision/once-as-root.sh', args: [options['timezone']]
  config.vm.provision 'shell', path: './vagrant/provision/as-root-mariadb-install.sh'
  config.vm.provision 'shell', path: './vagrant/provision/as-root-php7-install.sh'

  config.vm.post_up_message = "Dev app URL: http://#{domains[:frontend]}\nAdmin URL: http://#{domains[:backend]}\nTelegram URL: http://#{domains[:tg]}"
end
