#!/usr/bin/env python3
import json
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
REQUIRED = [
    ROOT / "SKILL.md",
    ROOT / "MANIFESTO.md",
    ROOT / "references" / "screen-map.md",
    ROOT / "templates" / "reports-card.stub.html",
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

    sample = json.loads((ROOT / "tests" / "input.sample.json").read_text(encoding="utf-8"))
    golden = json.loads((ROOT / "tests" / "golden" / "expected_output.json").read_text(encoding="utf-8"))
    if sample.get("skill") != "admin-reports-ui" or golden.get("risk_label") != "caution":
        print("Invalid sample/golden")
        return 1

    print("admin-reports-ui validation passed")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
