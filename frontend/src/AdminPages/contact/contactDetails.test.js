import React, { act } from 'react';
import { createRoot } from 'react-dom/client';
import { Simulate } from 'react-dom/test-utils';
import ContactDetails from './contactDetails';
import api from '../../API/api';

jest.mock('../../API/api', () => ({ getContactDetails: jest.fn(), updateContactDetails: jest.fn(), resetContactDetails: jest.fn() }));
const defaults = { address: 'Publisher office\nIndia', email: 'hello@example.com', phone: '+91 1234567890' };
const journals = [{ j_id: 4, j_name: 'Science', slug: 'science' }];
const response = contact => ({ data: { contact, defaults, journals } });
let container, root;
const change = (name, value) => act(() => Simulate.change(container.querySelector(`#contact-${name}`), { target: { name, value } }));
const selectJournal = async () => act(async () => Simulate.change(container.querySelector('#contact-scope'), { target: { value: '4' } }));
const button = text => [...container.querySelectorAll('button')].find(node => node.textContent.includes(text));
beforeEach(async () => {
    global.IS_REACT_ACT_ENVIRONMENT = true;
    jest.clearAllMocks(); window.confirm = jest.fn(() => true);
    api.getContactDetails.mockImplementation(scope => Promise.resolve(response(scope === 'default' ? defaults : {})));
    container = document.createElement('div'); document.body.appendChild(container); root = createRoot(container);
    await act(async () => root.render(<ContactDetails />));
});
afterEach(() => { act(() => root.unmount()); container.remove(); });

test('edits and saves the home default with trimmed contact values', async () => {
    expect(container.querySelector('#contact-address').value).toBe(defaults.address);
    expect(container.querySelector('#contact-address').required).toBe(true);
    change('address', ' New address ');
    expect(container.querySelector('[data-contact-preview="address"]').textContent).toBe('New address');
    api.updateContactDetails.mockResolvedValueOnce(response({ ...defaults, address: 'New address' }));
    await act(async () => Simulate.submit(container.querySelector('form')));
    expect(api.updateContactDetails).toHaveBeenCalledWith('default', { ...defaults, address: 'New address' });
    expect(container.textContent).toContain('Contact details saved.');
    expect(container.textContent).not.toContain('Unsaved changes');
});

test('journal blanks inherit the default and saving keeps blanks as overrides', async () => {
    await selectJournal();
    expect(container.querySelector('#contact-address').value).toBe('');
    expect(container.querySelector('#contact-address').required).toBe(false);
    expect(container.querySelector('[data-contact-preview="address"]').textContent).toBe(defaults.address);
    change('email', 'science@example.com');
    expect(container.querySelector('[data-contact-preview="email"]').textContent).toBe('science@example.com');
    api.updateContactDetails.mockResolvedValueOnce(response({ email: 'science@example.com' }));
    await act(async () => Simulate.submit(container.querySelector('form')));
    expect(api.updateContactDetails).toHaveBeenCalledWith('4', { address: '', email: 'science@example.com', phone: '' });
});

test('declining a scope switch preserves unsaved edits', async () => {
    change('address', 'Keep this draft');
    window.confirm.mockReturnValue(false);
    await selectJournal();
    expect(api.getContactDetails).toHaveBeenCalledTimes(1);
    expect(container.querySelector('#contact-scope').value).toBe('default');
    expect(container.querySelector('#contact-address').value).toBe('Keep this draft');
});

test('reset removes journal overrides and restores the shared preview', async () => {
    await selectJournal(); change('phone', '12345');
    api.resetContactDetails.mockResolvedValueOnce(response({}));
    await act(async () => button('Use default contact').click());
    expect(api.resetContactDetails).toHaveBeenCalledWith('4');
    expect(container.querySelector('#contact-phone').value).toBe('');
    expect(container.querySelector('[data-contact-preview="phone"]').textContent).toBe(defaults.phone);
    expect(container.textContent).toContain('This journal now uses the default contact.');
});

test('validation errors retain the draft so it can be corrected', async () => {
    change('email', 'draft@example.com');
    api.updateContactDetails.mockRejectedValueOnce({ response: { data: { errors: { email: ['Email is invalid.'] } } } });
    await act(async () => Simulate.submit(container.querySelector('form')));
    expect(container.querySelector('[role="alert"]').textContent).toBe('Email is invalid.');
    expect(container.querySelector('#contact-email').value).toBe('draft@example.com');
    expect(button('Save contact details').closest('fieldset').disabled).toBe(false);
});

test('a failed scope load can be retried without displaying stale contact values', async () => {
    api.getContactDetails.mockRejectedValueOnce(new Error('Unavailable'));
    await selectJournal();
    expect(container.querySelector('form')).toBeNull();
    await act(async () => button('Retry').click());
    expect(api.getContactDetails).toHaveBeenLastCalledWith('4');
    expect(container.querySelector('#contact-address').value).toBe('');
});
