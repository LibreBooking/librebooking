#!/usr/bin/python3 -ttu
# vim: ai ts=4 sts=4 et sw=4

import argparse
import dataclasses
import pathlib
import re
import sys


def parse_php_config_array(content: str) -> set[str]:
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


def to_env_key(config_key: str) -> str:
    return "LB_" + config_key.upper().replace(".", "_").replace("-", "_")

def extract_keys_from_env(env_path: pathlib.Path) -> set[str]:
    keys = set()
    for line in env_path.read_text().splitlines():
        line = line.strip()
        if not line or line.startswith("#"):
            continue
        match = re.match(r"([A-Z0-9_]+)\s*=", line)
        if match:
            keys.add(match.group(1))
    return keys

@dataclasses.dataclass(kw_only=True)
class ProgramArgs:
    config_path: pathlib.Path
    env_path: pathlib.Path

def parse_args() -> ProgramArgs:
    parser = argparse.ArgumentParser()
    parser.add_argument("config_path", type=pathlib.Path)
    parser.add_argument("env_path", type=pathlib.Path)
    args = parser.parse_args()
    return ProgramArgs(config_path=args.config_path, env_path=args.env_path)

def main() -> int:
    args = parse_args()
    config_text= args.config_path.read_text()
    config_keys = parse_php_config_array(config_text)
    env_keys = extract_keys_from_env(args.env_path)

    missing_keys = []
    for key in sorted(config_keys):
        env_key = to_env_key(key)
        if env_key not in env_keys:
            missing_keys.append((key, env_key))

    if missing_keys:
        print("❌ Missing environment variables:")
        for key, env_key in missing_keys:
            print(f"  - {env_key} (from '{key}')")
        return 1

    print("✅ All config keys are covered by environment variables.")
    return 0

if "__main__" == __name__:
    sys.exit(main())
