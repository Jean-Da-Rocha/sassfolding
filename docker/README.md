# Sassfolding Docker

## Table of contents

- [Introduction](#introduction)
- [Features](#features)
- [Architecture](#architecture)
- [Usage](#usage)
- [Advanced Topics](#advanced-topics)
- [Known Issues](#known-issues)
- [Acknowledgment](#acknowledgment)

## Introduction

> [!WARNING]
> This Docker setup has only been tested on the following operating systems:
> - **ZorinOS 17**
> - **Ubuntu 24.2**
> - **Sequoia 15.5**
> 
> It is not **production-ready** yet.
> Also, it only supports **macOS** and **Linux** based operating systems.

This project provides a standardized and streamlined Docker-based development environment tailored for Laravel +
Hybridly applications. It includes SSL support for local .test domains, isolated container and volume naming per
project, and a wide array of pre-configured services to boost development productivity out of the box.

> [!NOTE]
> This setup is not compatible with Laravel Sail as it follows a fundamentally different philosophy and architecture

This stack was built from scratch by someone with no prior Docker experience. The process surfaced several technical
challenges, which are discussed in the [advanced topics](#advanced-topics) section.

## Features

- ⚙️ Fully configurable via .env (versions, ports, project name, etc.)
- 📦 Unique container, volume, and network names per project (COMPOSE_PROJECT_NAME)
- 🌐 Local domain support without modifying /etc/hosts, thanks to DNSMasq and Traefik
- 🔒 Built-in SSL certificates for .test domains
- 🚀 First-class support for Hybridly and Laravel Horizon
- 📬 Mail and object storage (Mailpit, MinIO) included
- 🛠️ Graceful integration with modern development workflows

## Architecture

| Container    | Purpose                                                                      |
|--------------|------------------------------------------------------------------------------|
| **DNSMasq**  | Handles wildcard DNS resolution for **.test** domains without **/etc/hosts** |
| **Hybridly** | Main app container running both PHP and Node.js                              |
| **Horizon**  | Supervisor for managing Laravel queues with Redis                            |
| **Mailpit**  | Local SMTP server and email inbox for testing emails                         |
| **MinIO**    | S3-compatible object storage (for local file upload testing)                 |
| **MySQL**    | Relational database used by Laravel for both local and testing               |
| **Nginx**    | Serves PHP requests via FastCGI since Traefik does not support it            |
| **Redis**    | Used for queues, cache, and sessions                                         |
| **Traefik**  | Dynamic reverse proxy with built-in TLS and dashboard support                |

## Usage

The whole docker project is based on
the [COMPOSE_PROJECT_NAME](https://docs.docker.com/compose/how-tos/environment-variables/envvars/#compose_project_name)
variable, which is understood by Docker.

Basically, when you first pull the project and run the ```make install``` command, the script will use the slugified
version of your working directory. For example, when you pull this project, your containers, networks, volumes and URL
will be based on the **sassfolding** working directory:

- Container names: sassfolding-redis, sassfolding-hybridly, sassfolding-traefik, etc.
- Network name: project.sassfolding
- Volume names: sassfolding-redis-data, sassfolding-mail-data, etc.
- URL: app.sassfolding.test, horizon.sassfolding.test, mail.sassfolding.test, etc.

> [!NOTE]
> If you want to use a different name than the current working directory, you can set the **OVERRIDE_PROJECT_NAME**
> variable at the top of the Makefile. Its slugified version will take priority and be used as the
> **COMPOSE_PROJECT_NAME** throughout the project.

If you want to experiment with versions, ports, or other configurations, feel free to edit the corresponding values
in the **.env** file.

> [!TIP]
> Don't forget to run the ```make rebuild``` command to reflect your changes.

> [!CAUTION]
> COMPOSE_PROJECT_NAME= and HORIZON_PATH= are not supposed to be edited and should stay empty

## Advanced topics

In order to use clean **.test** domains locally like **app.sassfolding.test**, the stack relies on a lightweight
DNS server: **DNSMasq**. This setup allows requests for any subdomain under **.test** to be resolved to **localhost**
(127.0.0.1), ensuring that the DNS queries are handled locally without manually editing your /etc/hosts file.

### How it works

- The dnsmasq container listens on **53/udp** and **53/tcp** and handles requests to domains like ***.sassfolding.test**.
- It is configured to resolve any **.test** domains to your localhost (127.0.0.1)
- For all other domains, it forwards requests to upstream resolvers like **8.8.8.8** and **1.1.1.1**.

Here is the dnsmasq.conf used for the project:

```apacheconf
address=/test/127.0.0.1

no-hosts

server=8.8.8.8
server=8.8.4.4
server=1.1.1.1
server=1.0.0.1
```

### DNS Integration on Host Machine

To make your system aware of this custom DNS routing, the project provides two **Makefile** targets:

- **make setup-dns** - Adds a custom resolver configuration pointing **.test** to the **dnsmasq** container.
- **make restore-dns** - Removes this custom DNS configuration and restores your system’s default settings.

The behavior of these commands depends on your operating system and is determined by the **DNSMASQ_FORWARD_PORT**
variable, which specifies the DNS port to be used and is read by Docker within the **dnsmasq** container.

#### On macOS:

- Adds a file at **/etc/resolver/test** that directs DNS queries for .test domains to the container's IP, listening on
  port **53**, since macOS DNS resolver operates only on this port.
- Uses standard macOS DNS resolver behavior (no systemd needed).

#### On Linux:

- Creates a file at **/etc/systemd/resolved.conf.d/test.conf**.
- Instructs **systemd-resolved** to forward **.test** queries to the container, using port **5353** by default, which
  avoids conflicts with the system's primary port **53**.
- Restarts the **systemd-resolved** service to apply changes.

> [!IMPORTANT]
> **systemd-resolved** must be active. If it's not running, the script will warn you to start it manually.

### Why not just use /etc/hosts?

Manually editing **/etc/hosts** is tedious and static. This approach:

- Allows dynamic per-project domain routing.
- Avoids cluttering or conflicting with global system config.
- Enables isolated and portable development environments.

## Known Issues

While the setup is functional and stable for development, several technical caveats remain:

- **Non-optimized build size**: Some containers could be optimized in terms of size and how volumes are handled.
- **PHP and Node in the same container**: because Hybridly executes Artisan commands via Vite using the
  [**vite-plugin-run**](https://hybridly.dev/configuration/vite#run), PHP and Node must coexist in the same container.
  This design violates the separation of concerns.

## Acknowledgment

This project was heavily inspired by the amazing work of the [WAYOFDEV](https://github.com/wayofdev) team. Their
projects provided both technical reference and architectural guidance that made this setup possible:

- [WAYOFDEV - Docker Shared Service](https://github.com/wayofdev/docker-shared-service)
- [WAYOFDEV - Laravel Starter Tpl](https://github.com/wayofdev/laravel-starter-tpl)

If you’re interested in professional-grade Laravel setups with Docker, be sure to check out their repositories.
