#!/bin/bash
set -eo pipefail

# https://github.com/nginxinc/docker-nginx/blob/master/stable/debian/docker-entrypoint.sh
# https://github.com/docker-library/JOOMLAGOV_DB/blob/master/5.7/docker-entrypoint.sh


log() {
	local type="$1"; shift
	printf '%s [%s] [Entrypoint]: %s\n' "$(date --rfc-3339=seconds)" "$type" "$*"
}

note() {
	log Note "$@"
}

warn() {
	log Warn "$@" >&2
}

error() {
	log ERROR "$@" >&2
}

docker_process_init_files() {
	if find "/docker-entrypoint.d/" -mindepth 1 -maxdepth 1 -type f -print -quit 2>/dev/null | read v; then
		note "$0: /docker-entrypoint.d/ is not empty, will attempt to perform configuration"

		find "/docker-entrypoint.d/" -follow -type f -print | sort -V | while read -r f; do
			case "$f" in
				*.sh)
					if [ -x "$f" ]; then
						note "$0: Launching $f";
						"$f"
					else
						note "$0: Sourcing $f";
						. "$f"
					fi
					;;
				*.php)
					process_configuration $f;
					remove_folder installation;
					;;
				*) warn "$0: Ignoring $f";;
			esac
		done

		note "$0: Configuration complete; ready for start up"
	else
		note "$0: No files found in /docker-entrypoint.d/, skipping configuration"
	fi
}

process_configuration() {
	local file_path="$1"
	local filename=$(basename $file_path)
	local defined_envs

	if [ -z "${JOOMLA_DB_NAME}" ];then
		JOOMLA_DB_NAME="joomlagovdb"
		export JOOMLA_DB_NAME
	fi

	if [ -z "${JOOMLA_DB_PREFIX}" ];then
		JOOMLA_DB_PREFIX="xmx0n_"
		export JOOMLA_DB_PREFIX
	fi

	if [ -z "${JOOMLA_DB_HOST}" -o -z "${JOOMLA_DB_USER}" -o -z "${JOOMLA_DB_PASSWORD}" ]; then
		error "Unable to init default configuration.\n\tYou need to specify JOOMLA_DB_HOST, JOOMLA_DB_USER, JOOMLA_DB_PASSWORD environment variables."
		return 0
	fi

	defined_envs=$(printf '${%s} ' $(env | cut -d= -f1))

	note "$0: Running envsubst on $file_path"
	envsubst "$defined_envs" < "$file_path" > "$filename"
}

remove_folder() {
	rm -rf $1
}

docker_process_init_files

exec "$@"
