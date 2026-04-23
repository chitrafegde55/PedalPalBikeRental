---
name: generate-prd
description: Use this prompt to generate or update the Product Requirements Document for PedalPal modernization
---

# PedalPal PRD Generation Guide

## Overview

The existing PedalPal PRD is located at: `docs/01_PedalPal_PRD.md`

This document provides comprehensive specifications for the modernized bike rental management system. Before generating new PRD content, review the existing document to understand:
- Business goals and user stories
- Feature requirements and acceptance criteria
- Scope and constraints
- Success metrics

## When to Generate/Update PRD

### Generate new PRD when:
1. Adding major new features beyond current scope
2. Changing core business logic (pricing, availability, etc.)
3. Expanding to new markets or use cases
4. Pivoting from current vision
5. Adding new user roles or workflows

### Update existing PRD when:
1. Clarifying ambiguous requirements
2. Adding implementation notes
3. Refining user acceptance criteria
4. Documenting discovered constraints
5. Version bumping after releases

## PRD Structure (Reference Current Document)

Existing PRD follows this structure:
```
- Executive Summary
  - Problem Statement
  - Solution Overview
  - Key Benefits
  
- Goals & Objectives
  - Primary Goals
  - Success Criteria
  - Performance Metrics
  
- User Stories & Use Cases
  - Customer Stories (browsing, renting, paying)
  - Staff Stories (inventory, returns, analytics)
  - Admin Stories (system management)
  
- Feature Requirements
  - Functional Requirements
  - Non-Functional Requirements
  - API Specifications
  - Database Schema
  
- Acceptance Criteria
  - Feature-specific criteria
  - Quality standards
  - Performance targets
  
- Out of Scope
  - What's NOT included
  - Future considerations
  
- Technical Assumptions
  - Tech stack choices
  - Architecture patterns
  - Deployment model
```

## Current System Requirements (For Reference)

### Functional Requirements:
1. **Browse Bikes**: Filter by type (Beach Cruiser, Mountain Bike), availability
2. **Rent Bikes**: Check availability, confirm rental, return dates
3. **Accessories**: Browse and bundle with rentals, apply discounts
4. **Inventory Management**: Admin view of stock, rental history
5. **Reporting**: Activity logs, revenue tracking, utilization rates

### Non-Functional Requirements:
1. **Performance**: API response < 200ms, page load < 3 seconds
2. **Scalability**: Handle 100 concurrent users
3. **Availability**: 99.5% uptime target
4. **Security**: User authentication, secure payment handling
5. **Responsive**: Desktop, tablet, mobile compatible

### Tech Stack (Reference - Don't Change):
- **Frontend**: Vue 3, Vue Router, Axios, Vite
- **Backend**: PHP 8.2+, Laravel structure
- **Database**: SQLite (dev), PostgreSQL (production)
- **Testing**: PHPUnit 11.5.55+
- **Build**: Vite 5.4.21

## PRD Generation Prompt Template

When asking AI to generate/update PRD content, use this structure:

```markdown
Please generate/update the PRD section for [FEATURE_NAME]:

Context:
- User story: [specific user need]
- Business goal: [why this matters]
- Current implementation: [existing code/behavior]
- Constraint: [any limitations]

Requirements to address:
- [Specific requirement 1]
- [Specific requirement 2]
- [Acceptance criteria]

Format: Follow existing PRD structure in docs/01_PedalPal_PRD.md
Include: User stories, acceptance criteria, technical considerations
```

## Common PRD Updates

### Example 1: Adding Payment Integration
```markdown
Section: Feature Requirements > Payment Processing

User Story:
- As a customer, I want to complete rental transactions securely
- As an admin, I want to view payment history and reconciliation reports

Acceptance Criteria:
- [ ] Support credit/debit card payments
- [ ] Transaction records stored securely
- [ ] Confirmation emails sent
- [ ] Refunds handled within 24 hours
- [ ] PCI compliance maintained

Technical Considerations:
- Integrate Stripe/PayPal API
- Encrypted payment tokens
- Audit logging for compliance
- Handle payment failures gracefully
```

### Example 2: Adding Mobile App
```markdown
Section: Goals & Objectives > New Platforms

Business Goal:
- Increase customer engagement through native mobile apps
- Reach on-the-go renters during off-peak web hours

Scope:
- iOS app built with React Native or Swift
- Android app built with Kotlin or React Native
- Share core business logic from web backend via APIs
- Feature parity with web initially

Success Metrics:
- 50% of rentals from mobile within 6 months
- App rating > 4.5 stars
- DAU (daily active users) > 1000
```

### Example 3: Adding Ride-Along Tours
```markdown
Section: Feature Requirements > New Service Type

Feature: Guided Bike Tours

User Story:
- As a customer, I want to book a guided tour experience
- As a guide, I want to manage tour schedules and groups
- As an admin, I want to track tour profitability

Acceptance Criteria:
- [ ] Tour packages displayed with description, photos, pricing
- [ ] Date/time picker for tour selection
- [ ] Group size limits enforced
- [ ] Automatic cancellation if group too small
- [ ] Guide assignment and tracking
- [ ] Post-tour feedback collection
```

## PRD Best Practices

### DO:
- ✅ Use clear, specific language
- ✅ Include measurable success criteria
- ✅ Reference existing architecture/code patterns
- ✅ Identify dependencies and constraints
- ✅ Group related requirements together
- ✅ Include mock-ups or wireframes reference
- ✅ Document assumptions explicitly
- ✅ Version and date document updates

### DON'T:
- ❌ Be vague ("make it better")
- ❌ Mix implementation details with requirements
- ❌ Ignore existing system constraints
- ❌ Leave acceptance criteria unmeasurable
- ❌ Forget to document what's OUT OF SCOPE
- ❌ Create requirements that conflict with current PRD
- ❌ Ignore performance/scalability implications

## Integration with Development

### PRD → Code Workflow:
1. **PRD Finalized**: Lock version in `docs/01_PedalPal_PRD.md`
2. **Create User Stories**: Break requirements into JIRA/GitHub issues
3. **Update Wireframes**: Modify `docs/wireframe-design-specs/` as needed
4. **Update Architecture**: Reference in `docs/02_PedalPal_Architecture.md`
5. **Write Tests First**: PHPUnit tests from acceptance criteria
6. **Develop Features**: Implementation guided by tests
7. **Update Tests**: Ensure PRD criteria met

### Versioning:
```markdown
# PRD Version History
- v1.0 (Initial): Core bike rental system
- v2.0 (Modernization): Vue 3 frontend, PHP 8.2 backend
- v2.1 (Planned): Payment integration
- v3.0 (Planned): Mobile app support
```

## PRD Validation Checklist

Before submitting new PRD content:
- [ ] Aligns with existing business goals
- [ ] Technical feasibility verified
- [ ] No conflicts with current architecture
- [ ] Acceptance criteria are specific and measurable
- [ ] Performance implications considered
- [ ] Security/privacy requirements identified
- [ ] Dependencies documented
- [ ] Risk factors acknowledged
- [ ] Success metrics defined
- [ ] Out of scope clearly listed

## Referencing in Code

When implementing from PRD:
```php
// Service layer reference to PRD requirement
/**
 * Rental booking - PRD requirement: Feature > Bike Rentals > Manage Availability
 * Acceptance Criteria: Check real-time availability, prevent overbooking
 */
public function rentBike(int $bikeId, DateTime $startDate, DateTime $endDate): RentalConfirmation
{
    // Implementation based on PRD spec
}
```

```vue
<!-- Vue component reference to PRD wireframe -->
<!-- See: docs/wireframe-design-specs/PedalPal_Wireframe_Spec.html - Bike Catalog Page -->
<!-- PRD Feature: Browse Bikes > Filter and Display -->
<template>
  <!-- Component implementation following both PRD and wireframe -->
</template>
```

## Next PRD Updates (Planned)

Based on modernization roadmap:
1. **Phase 2**: Payment integration (credit cards, digital wallets)
2. **Phase 3**: Mobile app support (React Native)
3. **Phase 4**: AI-powered recommendations
4. **Phase 5**: Multi-location support

When ready to work on these, generate PRD sections using the template above.