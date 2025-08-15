#!/usr/bin/python3 -ttu
# vim: ai ts=4 sts=4 et sw=4

import argparse
import dataclasses
import pathlib
from typing import Set
import re
import sys


def parse_php_config_array(content: str) -> Set[str]:
    keys = set()
    stack = []
    lines = content.splitlines()

    skipping_first_level = True
    for line in lines:
        line = line.strip()

        if not line or line.startswith("//") or line.startswith("#"):
            continue

        match = re.match(r"'([^']+)'\s*=>", line)
        if match:
            key = match.group(1)

            if "=> [" in line or line.endswith("["):
                stack.append(key)
                if skipping_first_level and key == "settings":
                    stack = []
                    continue
                skipping_first_level = False
            else:
                full_key = ".".join(stack + [key])
                keys.add(full_key)

        if line in ("]", "],"):
            if stack:
                stack.pop()

    return keys


def parse_config_keys_php(content: str) -> Set[str]:
    keys = set()
    pattern = re.compile(r"'key'\s*=>\s*'([^']+)'")
    for match in pattern.finditer(content):
        keys.add(match.group(1))

    return keys

@dataclasses.dataclass(kw_only=True)
class ProgramArgs:
    config_path: pathlib.Path
    config_keys_path: pathlib.Path

def parse_args() -> ProgramArgs:
    parser = argparse.ArgumentParser()
    parser.add_argument("config_path", type=pathlib.Path)
    parser.add_argument("config_keys_path", type=pathlib.Path)
    args = parser.parse_args()
    return ProgramArgs(config_path=args.config_path, config_keys_path=args.config_keys_path)

def main() -> int:
    args = parse_args()
    config_content= args.config_path.read_text()
    config_keys_content = args.config_keys_path.read_text()


    config_keys = parse_php_config_array(config_content)
    defined_keys = parse_config_keys_php(config_keys_content)

    missing_in_keys = sorted(config_keys - defined_keys)

    if missing_in_keys:
        print("❌ Config keys missing in ConfigKeys.php:")
        for key in missing_in_keys:
            print(f"  - {key}")
        return 1

    print("✅ All config.dist.php keys are defined in ConfigKeys.php.")
    return 0

if "__main__" == __name__:
    sys.exit(main())
