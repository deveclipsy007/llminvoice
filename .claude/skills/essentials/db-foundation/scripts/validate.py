#!/usr/bin/env python3
import json
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
REQUIRED = [
    ROOT / "SKILL.md",
    ROOT / "MANIFESTO.md",
    ROOT / "templates" / "migration.stub.sql",
    ROOT / "schemas" / "output.schema.json",
    ROOT / "tests" / "input.sample.json",
    ROOT / "tests" / "golden" / "expected_output.json",
]


def main() -> int:
    missing = [str(p) for p in REQUIRED if not p.exists()]
    if missing:
        print("Missing files:")
        for item in missing:
            print(f"- {item}")
        return 1

    with (ROOT / "schemas" / "output.schema.json").open("r", encoding="utf-8") as f:
        json.load(f)
    with (ROOT / "tests" / "input.sample.json").open("r", encoding="utf-8") as f:
        data = json.load(f)
    with (ROOT / "tests" / "golden" / "expected_output.json").open("r", encoding="utf-8") as f:
        golden = json.load(f)

    if data.get("skill") != "db-foundation":
        print("input.sample.json: invalid skill name")
        return 1
    if golden.get("risk_label") != "caution":
        print("expected_output.json: risk_label must be caution")
        return 1

    print("db-foundation validation passed")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
