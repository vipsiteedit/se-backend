#!/usr/bin/env python3
from pathlib import Path
import re
from xml.sax.saxutils import escape

from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Preformatted
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont


ROOT = Path(__file__).resolve().parents[1]
INPUT = ROOT / "docs" / "urlfilter-guide-ru.md"
OUTPUT = ROOT / "docs" / "urlfilter-guide-ru.pdf"


def register_fonts():
    font_candidates = [
        ("DejaVuSans", "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf"),
        ("DejaVuSansMono", "/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf"),
    ]
    for name, path in font_candidates:
        p = Path(path)
        if p.exists():
            pdfmetrics.registerFont(TTFont(name, str(p)))
        else:
            raise FileNotFoundError(f"Font not found: {path}")


def styles():
    return {
        "h1": ParagraphStyle(
            "h1", fontName="DejaVuSans", fontSize=17, leading=22, spaceAfter=10, spaceBefore=6
        ),
        "h2": ParagraphStyle(
            "h2", fontName="DejaVuSans", fontSize=13, leading=17, spaceAfter=7, spaceBefore=8
        ),
        "h3": ParagraphStyle(
            "h3", fontName="DejaVuSans", fontSize=11, leading=14, spaceAfter=5, spaceBefore=6
        ),
        "p": ParagraphStyle(
            "p", fontName="DejaVuSans", fontSize=10, leading=14, spaceAfter=5
        ),
        "code": ParagraphStyle(
            "code",
            fontName="DejaVuSansMono",
            fontSize=8.7,
            leading=11,
            leftIndent=10,
            rightIndent=10,
            spaceBefore=4,
            spaceAfter=8,
        ),
    }


def add_paragraph(story, text, style):
    if text.strip():
        story.append(Paragraph(escape(text.strip()), style))


def parse_markdown(md_text, st):
    lines = md_text.splitlines()
    story = []
    i = 0

    while i < len(lines):
        line = lines[i]
        stripped = line.strip()

        if stripped == "":
            i += 1
            continue

        if stripped.startswith("```"):
            i += 1
            code_lines = []
            while i < len(lines) and not lines[i].strip().startswith("```"):
                code_lines.append(lines[i].rstrip("\n"))
                i += 1
            if i < len(lines):
                i += 1
            story.append(Preformatted("\n".join(code_lines), st["code"]))
            continue

        if stripped.startswith("# "):
            add_paragraph(story, stripped[2:], st["h1"])
            i += 1
            continue

        if stripped.startswith("## "):
            add_paragraph(story, stripped[3:], st["h2"])
            i += 1
            continue

        if stripped.startswith("### "):
            add_paragraph(story, stripped[4:], st["h3"])
            i += 1
            continue

        if re.match(r"^-\s+", stripped):
            # consecutive bullet items
            while i < len(lines) and re.match(r"^-\s+", lines[i].strip()):
                item_text = re.sub(r"^-\s+", "", lines[i].strip())
                add_paragraph(story, "• " + item_text, st["p"])
                i += 1
            story.append(Spacer(1, 2))
            continue

        if re.match(r"^\d+\.\s+", stripped):
            while i < len(lines) and re.match(r"^\d+\.\s+", lines[i].strip()):
                add_paragraph(story, lines[i].strip(), st["p"])
                i += 1
            story.append(Spacer(1, 2))
            continue

        # regular paragraph: merge until blank or special block
        para = [stripped]
        i += 1
        while i < len(lines):
            nxt = lines[i].strip()
            if (
                nxt == ""
                or nxt.startswith("#")
                or nxt.startswith("```")
                or re.match(r"^-\s+", nxt)
                or re.match(r"^\d+\.\s+", nxt)
            ):
                break
            para.append(nxt)
            i += 1
        add_paragraph(story, " ".join(para), st["p"])

    return story


def main():
    if not INPUT.exists():
        raise FileNotFoundError(f"Input markdown not found: {INPUT}")

    register_fonts()
    st = styles()

    text = INPUT.read_text(encoding="utf-8")
    story = parse_markdown(text, st)

    doc = SimpleDocTemplate(
        str(OUTPUT),
        pagesize=A4,
        leftMargin=36,
        rightMargin=36,
        topMargin=36,
        bottomMargin=36,
        title="Инструкция по URL-фильтру",
        author="SiteEdit Core",
    )
    doc.build(story)
    print(f"PDF generated: {OUTPUT}")


if __name__ == "__main__":
    main()
