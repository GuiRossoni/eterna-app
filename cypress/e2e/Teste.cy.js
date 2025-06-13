describe('Autenticação', () => {
  it('Deve cadastrar um novo usuário', () => {
    cy.visit('/account/register');
    cy.get('input[name="name"]').type('Usuário Cypress');
    cy.get('input[name="email"]').type('cypress_' + Date.now() + '@test.com');
    cy.get('input[name="password"]').type('senha12345');
    cy.get('input[name="password_confirmation"]').type('senha12345');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/account/login');
    cy.contains('Acessar');
  });

  it('Deve logar com usuário comum', () => {
    cy.login();
    cy.url().should('include', '/account/profile');
    cy.contains('Profile');
  });

  it('Deve logar como admin', () => {
    cy.loginAsAdmin();
    cy.url().should('not.include', '/account/login');
    cy.contains('Profile');
  });

  it('Deve logar com usuário e senha customizados', () => {
    cy.loginCom('usercy@eterna.com', '943768hgkg');
    cy.url().should('not.include', '/account/login');
    cy.contains('Profile');
  });
});

describe('Perfil do Usuário', () => {
  beforeEach(() => {
    cy.login();
  });

  it('Deve exibir e atualizar o perfil', () => {
    cy.visit('/account/profile');
    cy.get('input[name="name"]').clear().type('Novo Nome Cypress');
    cy.get('button').contains('Atualizar').click();
    cy.contains('Atualizar');
    cy.get('input[name="name"]').should('have.value', 'Novo Nome Cypress');
  });
});

describe('Livros', () => {
  beforeEach(() => {
    cy.loginAsAdmin();
  });

  it('Deve acessar a lista de livros', () => {
    cy.visit('/account/books');
    cy.contains('Livros');
  });

  it('Deve criar um novo livro', () => {
    cy.visit('/account/books/create');
    cy.get('input[name="title"]').type('Livro Cypress');
    cy.get('input[name="author"]').type('Autor Cypress');
    cy.get('textarea[name="description"]').type('Descrição Cypress');
    cy.get('select[name="status"]').select('1');
    cy.get('button').contains('Criar').click();
    cy.url().should('include', '/account/books');
    cy.contains('Livro Cypress');
  });
});

describe('Avaliações', () => {
  beforeEach(() => {
    cy.login();
  });

  it('Deve criar uma avaliação para um livro', () => {
    cy.visit('/');
    cy.get('.card a').first().click();
    cy.contains('Criar Review').click();
    cy.get('textarea[name="review"]').type('Avaliação Cypress');
    cy.get('select[name="rating"]').select('5');
    cy.get('button[type="submit"]').contains('Enviar').click();
    cy.contains('Avaliação Cypress');
  });

  it('Deve listar minhas avaliações', () => {
    cy.visit('/account/my-reviews/list');
    cy.contains('Minhas Avaliações');
  });
});
