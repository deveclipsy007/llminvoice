#!/usr/bin/env python3
import json
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
REQUIRED = [
    ROOT / "SKILL.md",
    ROOT / "MANIFESTO.md",
    ROOT / "templates" / "ai-provider.stub.php",
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

    with (ROOT / "tests" / "input.sample.json").open("r", encoding="utf-8") as f:
        sample = json.load(f)
    with (ROOT / "tests" / "golden" / "expected_output.json").open("r", encoding="utf-8") as f:
        golden = json.load(f)

    if sample.get("skill") != "ai-analysis-pipeline":
        print("Invalid sample skill")
        return 1
    if golden.get("risk_label") != "caution":
        print("risk label mismatch")
        return 1

    print("ai-analysis-pipeline validation passed")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
