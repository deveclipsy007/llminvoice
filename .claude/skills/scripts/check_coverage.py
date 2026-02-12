#!/usr/bin/env python3
import json
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
MATRIX = ROOT / "coverage-matrix.json"


def main() -> int:
    data = json.loads(MATRIX.read_text(encoding="utf-8"))
    modules = data.get("modules", [])

    partial = [m for m in modules if m.get("status") == "partial"]
    missing = [m for m in modules if m.get("status") == "missing"]

    print(f"Total modules: {len(modules)}")
    print(f"Partial: {len(partial)}")
    print(f"Missing: {len(missing)}")

    if partial:
        print("\nModules parciais:")
        for m in partial:
            print(f"- {m['module']}")

    if missing:
        print("\nModules ausentes:")
        for m in missing:
            print(f"- {m['module']}")

    if partial or missing:
        return 1

    print("\nCobertura completa para replicacao.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
