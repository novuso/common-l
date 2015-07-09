# project settings
project_name = "common"
project_hostname = "common"
project_root = "/vagrant"

# server settings
server_box = "ubuntu/trusty64"
server_ip = "192.168.50.11"
server_cpus = "1"
server_memory = "1024"

# vagrant config
Vagrant.configure(2) do |config|

    # server operating system
    config.vm.box = server_box

    # server networking and sync
    config.vm.hostname = project_hostname
    config.vm.network :private_network, ip: server_ip
    config.vm.synced_folder ".", project_root, id: "core",
        :nfs         => true,
        :nfs_udp     => false,
        :nfs_version => 4

    # virtualbox setup
    config.vm.provider :virtualbox do |vb|
        vb.name = project_name
        vb.customize ["modifyvm", :id, "--cpus", server_cpus]
        vb.customize ["modifyvm", :id, "--memory", server_memory]
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
    end

    # provisioning
    config.vm.provision :shell,
        path: "app/script/provision.sh",
        args: [project_root],
        privileged: false

end

# vi: set ft=ruby :
