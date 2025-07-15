#!/usr/bin/python3 -ttu
# vim: ai ts=4 sts=4 et sw=4

import argparse
import dataclasses
import pathlib
import pprint
import re
import sys


def main() -> int:
    args = parse_args()

    dist_path = args.config_dir / "config.dist.php"
    devel_path = args.config_dir / "config.devel.php"
    dist_configs = read_config_values(filepath=dist_path)
    devel_configs = read_config_values(filepath=devel_path)

    exit_value = 0
    missing_devel = dist_configs - devel_configs
    if missing_devel:
        print(f"Config values set in {dist_path} but not in {devel_path}")
        pprint.pprint(sorted(missing_devel))
        print()
        exit_value = 1

    missing_dist = devel_configs - dist_configs
    if missing_dist:
        print(f"Config values set in {devel_path} but not in {dist_path}")
        pprint.pprint(sorted(missing_dist))
        exit_value = 1
    return exit_value


def read_config_values(*, filepath: pathlib.Path) -> set[str]:
    configs = set()
    with open(filepath) as in_file:
        for line in in_file.readlines():
            line = line.strip()
            if not line.startswith("$conf"):
                continue
            result = re.search(r"^\$conf\S+", line)
            if not result:
                raise ValueError(f"Invalid configuration line: {line}")
            conf_value = result.group()[5:]
            configs.add(conf_value)
    return configs


@dataclasses.dataclass(kw_only=True)
class ProgramArgs:
    config_dir: pathlib.Path


def parse_args() -> ProgramArgs:
    parser = argparse.ArgumentParser()

    parser.add_argument("config_dir")

    args = parser.parse_args()
    args.config_dir = pathlib.Path(args.config_dir).expanduser().resolve()
    return ProgramArgs(**vars(args))


if "__main__" == __name__:
    sys.exit(main())
