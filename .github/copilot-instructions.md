---
description: Repository-wide AI guidance for modernizing the legacy PedalPal Bike Rental assignment and creating the associated documentation and design artifacts.
applyTo: '**/*'
---

This repository is a legacy PHP bike rental module that should be treated as a modernization assignment.

When generating code or documentation:
- Use modern PHP 8+ backend architecture, preferably Laravel-style and API-first.
- Modernize the frontend with Vue 3, responsive design, and component-driven UIs.
- Produce documentation in `docs/` and root-level README sections that describe what changed, why, and how AI assisted the work.
- Keep improvements focused on maintainability, scalability, security, and usability.
- When asked, generate wireframe specs, PRDs, architecture docs, and AI usage logs for the assignment.

Documentation and Requirements:
- Create a README that includes an overview of the modernization, architecture, design documents, tech stacks used, folder structures, pain points removed, AI usage details, assumptions, and integration notes.
- Use the provided templates in `docs/` for the PRD, architecture, README, AI usage log, and wireframe specs.
- PRD document should outline the problem statement, goals, user stories, and acceptance criteria for the modernization.
- Architecture document should describe the new structure, design patterns, and how it improves maintainability and scalability.
- For code generation, prefer clear, maintainable implementations that align with modern PHP and Vue best practices, while preserving the core functionality of the legacy module.
- When generating frontend code, focus on component-driven design, responsive layouts, and modern UX patterns
- If PRD docuemnt is long, shard it into multiple sections with clear headings and subheadings for readability.
- Create specs and user stories that are implementation-ready and can be directly used for development.
- When generating code, avoid unnecessary duplication of legacy patterns and instead focus on modern, clean implementations