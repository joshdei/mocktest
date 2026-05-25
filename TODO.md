# TODO - Study Partner Challenge System (PsalmEdu)

## Backend + DB
- [ ] Create migration: `study_challenges` table
- [ ] Create model: `StudyChallenge`
- [ ] Create controller: `ChallengeController`
  - [ ] `findOpponent()`
  - [ ] `sendChallenge()`
  - [ ] `challengerSubmit(Request, Challenge)`
  - [ ] `opponentPlay(Challenge)`
  - [ ] `opponentSubmit(Request, Challenge)`

## Emails
- [ ] Create mail classes:
  - [ ] `ChallengeInviteMail` (hook line must be exact + prominent + CTA button)
  - [ ] `ChallengeResultMail`
- [ ] Create email blade templates:
  - [ ] `resources/views/emails/challenges/invite.blade.php`
  - [ ] `resources/views/emails/challenges/result.blade.php`

## Routes
- [ ] Update `routes/web.php` with challenge routes

## Views
- [ ] Update dashboard VS card in `resources/views/dashboard/home.blade.php`:
  - [ ] no challenge → Find Opponent
  - [ ] pending → Send Challenge
  - [ ] waiting opponent → Waiting message + accept/play
  - [ ] completed win → winner banner + rematch
  - [ ] completed loss → loser banner + rematch
  - [ ] completed draw → “It’s a Draw! 🤝” + rematch
- [ ] Create `resources/views/challenges/play.blade.php` (reuse mock exam UI; same question_set)
- [ ] Create `resources/views/challenges/result.blade.php` (score comparison)

## Console Command
- [ ] Create Artisan command: `php artisan challenges:expire`
  - expires pending/challenger_played challenges after 48 hours

## Verify
- [ ] Migrate DB + smoke test:
  - [ ] Create pending challenge from Find Opponent
  - [ ] Challenger plays → invite email contains exact hook line
  - [ ] Opponent plays same question_set → winner/draw logic + dashboard update
  - [ ] Rematch creates a new challenge
