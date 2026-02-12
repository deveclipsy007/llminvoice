#!/usr/bin/env python3
import json
import subprocess
import sys
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
INDEX_FILE = ROOT / "index.json"


def load_index() -> dict:
    with INDEX_FILE.open("r", encoding="utf-8") as f:
        return json.load(f)


def run_validator(skill_path: Path) -> tuple[bool, str]:
    validator = skill_path / "scripts" / "validate.py"
    if not validator.exists():
        return False, f"missing validator: {validator}"

    proc = subprocess.run(
        [sys.executable, str(validator)],
        cwd=ROOT.parents[0],
        capture_output=True,
        text=True,
    )
    output = (proc.stdout + proc.stderr).strip()
    return proc.returncode == 0, output


def main() -> int:
    index = load_index()
    failures = []

    for skill in index.get("skills", []):
        rel = skill.get("path", "")
        skill_path = ROOT.parents[0] / rel
        ok, output = run_validator(skill_path)
        name = skill.get("name", rel)

        if ok:
            print(f"[OK] {name}")
        else:
            print(f"[FAIL] {name}")
            if output:
                print(output)
            failures.append(name)

    if failures:
        print("\nFalharam:")
        for name in failures:
            print(f"- {name}")
        return 1

    print("\nTodas as skills passaram na validacao.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
