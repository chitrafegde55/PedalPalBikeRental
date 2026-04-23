---
name: code-review
description: Use this prompt for code reviews focusing on Vue 3 patterns, PHP 8.2+ modern syntax, and testing best practices
---

# PedalPal Code Review Guidelines

## Review Focus Areas

### Vue 3 Component Quality
✅ **DO**:
- Use `<script setup lang="ts">` with TypeScript types
- Extract shared logic into composables in `resources/js/composables/`
- Handle loading, error, and empty states explicitly in template
- Use computed properties for derived data
- Implement proper prop validation with types
- Use two-way binding sparingly; prefer explicit emits

❌ **DON'T**:
- Leave components without error boundaries
- Use large watchers without unwatch cleanup
- Mix UI logic with business logic
- Skip TypeScript typing in favor of implicit `any`
- Create global mutable state without Pinia store

### PHP 8.2+ Modern Patterns
✅ **DO**:
- Use modern syntax: typed properties, match expressions, nullsafe operator
- Use closure syntax with `use` clause instead of deprecated `create_function()`
- Implement strongly typed method parameters and return types
- Use constructor property promotion (`public function __construct(public Service $service) {}`)
- Follow service-oriented architecture with dependency injection

❌ **DON'T**:
- Use deprecated functions (e.g., `create_function()`, `ereg*()`, `split()`)
- Mix array syntax; use modern `[]` consistently
- Leave methods without return type hints
- Directly access globals; use dependency injection

Example of modern PHP:
```php
// ✅ Good: Modern PHP 8.2+
class AccessoryService {
    public function __construct(
        private AccessoryRepository $repository
    ) {}

    public function getPriceWithDiscount(Accessory $item, float $discountRate): float {
        return match(true) {
            $discountRate > 0.15 => throw new InvalidArgumentException('Max discount 15%'),
            default => $item->price * (1 - $discountRate)
        };
    }
}
```

### Service Layer Architecture
✅ **DO**:
- Keep services focused on single business domain
- Use repository pattern for data access
- Validate inputs at service layer entry points
- Return consistent response types
- Handle all business logic in services, not controllers

❌ **DON'T**:
- Mix data access with business logic
- Throw generic exceptions
- Leave services state-dependent
- Create untestable service interdependencies

### Database & Repositories
✅ **DO**:
- Use parameterized queries preventing SQL injection
- Implement consistent error handling
- Return or throw predictable types
- Keep repositories clean - minimal logic beyond CRUD

❌ **DON'T**:
- Concatenate SQL with user input
- Silent failures in repository methods
- Leave transaction handling incomplete

### Testing Standards
✅ **DO**:
- Write tests for all service methods (100% coverage target)
- Use descriptive test names: `testShouldXWhenY()`
- Test success AND failure scenarios
- Mock external dependencies
- Keep tests independent and deterministic

Example test structure:
```php
public function testShouldRentBikeWhenAvailable(): void {
    // Arrange
    $bike = $this->createBike(['available' => true]);
    
    // Act
    $result = $this->service->rentBike($bike->id);
    
    // Assert
    $this->assertTrue($result->success);
}
```

❌ **DON'T**:
- Write tests without clear Arrange-Act-Assert structure
- Create test interdependencies
- Use real database or API calls in unit tests
- Leave untested error paths

### CSS & Responsive Design
✅ **DO**:
- Use CSS variables for theming consistency
- Define gradients in global `app.css` for component reuse
- Test responsive behavior at 768px tablet breakpoint
- Ensure semantic HTML with proper ARIA labels

❌ **DON'T**:
- Use hardcoded colors instead of CSS variables
- Create non-responsive layouts
- Forget mobile-first CSS approach
- Skip accessibility considerations

## Review Checklist

### Vue Components
- [ ] Component uses `<script setup lang="ts">` syntax
- [ ] All props/emits are explicitly typed
- [ ] Loading, error, and empty states handled
- [ ] No logic duplication (composables extracted)
- [ ] Accessibility: semantic HTML, aria-labels where needed
- [ ] Responsive design tested (desktop, tablet, mobile)

### PHP Services & Handlers
- [ ] Type hints on all parameters and returns
- [ ] No deprecated PHP functions used
- [ ] Dependency injection used, no globals
- [ ] Error handling clear and specific
- [ ] Business logic in services, not handlers
- [ ] All edge cases validated

### Tests
- [ ] Every service method has tests
- [ ] Both success and failure scenarios covered
- [ ] Test names clearly describe behavior
- [ ] Mocks used for dependencies
- [ ] All tests pass (`vendor/bin/phpunit`)
- [ ] Coverage report shows 80%+ target

### Database & Data Access
- [ ] Parameterized queries (no string concatenation)
- [ ] Consistent error handling
- [ ] Seed data reflects real use cases
- [ ] Migrations reversible and documented

## Common Issues & Fixes

### Issue: Vue component has N+1 API calls
**Fix**: Use composable to batch queries or implement pagination at service layer

### Issue: Service method with no error handling
**Fix**: Add specific exception types and validation at entry point

### Issue: Component state mutation without emits
**Fix**: Use props/emits contract or lift state to parent/store

### Issue: Mixed array syntax (`array()` and `[]`)
**Fix**: Standardize on modern `[]` syntax

### Issue: Hard-coded color values in components
**Fix**: Use CSS variables from `app.css` design system

## Priority Review Points

🔴 **Critical** (Block merge):
- Security: SQL injection, exposed secrets
- Breaking API changes
- Deprecated PHP functions
- Silent failures or unhandled errors

🟡 **Important** (Needs discussion):
- Missing test coverage
- Performance issues (N+1, inefficient algorithms)
- Architecture violations
- Inaccessible UI components

🟢 **Suggestion** (Non-blocking):
- Code style inconsistencies
- Naming improvements
- Documentation completeness
- Optimization opportunities