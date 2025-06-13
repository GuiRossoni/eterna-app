Cypress.Commands.add('login', () => {
  cy.visit('/account/login');
  cy.get('input[name="email"]').type('usercy@eterna.com');
  cy.get('input[name="password"]').type('943768hgkg');
  cy.get('button[type="submit"]').click();
});

Cypress.Commands.add('loginAsAdmin', () => {
  cy.visit('/account/login');
  cy.get('input[name="email"]').type('admincy@eterna.com');
  cy.get('input[name="password"]').type('943768hgkg');
  cy.get('button[type="submit"]').click();
});

Cypress.Commands.add('loginCom', (email, senha) => {
  cy.visit('/account/login');
  cy.get('input[name="email"]').type(email);
  cy.get('input[name="password"]').type(senha);
  cy.get('button[type="submit"]').click();
});