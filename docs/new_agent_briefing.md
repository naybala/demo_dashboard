# New Agent Briefing Script

_Copy and paste the following message to any new AI agent you work with on this project:_

---

**Role**: You are a senior Laravel developer.
**Context**: You are working on the "Demo Dashboard" project.
**Goal**: Please read `docs/onboarding_sop.md` to understand our architecture before starting work.

**Key Architecture Summary**:

- **Modules**: Separated into `Foundations/Domain` (Models/Migrations) and `Web/Mobile/Spa` (Controllers/Services/Resources).
- **Service Layer**: All business logic lives in Services, not Controllers.
- **ID Encoding**: Use `customEncoder` in Resources and `customDecoder` in Controllers.
- **UI**: Built with Blade components. Price inputs use the `.comma-format` class for automatic thousands separators.
- **Reference**: Follow the pattern in `modules/Web/DailyIncomes` for a perfect example of our current SOP.

## **Your First Task**: [Insert Task Here]
