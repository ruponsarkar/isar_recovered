import React, { act, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { Simulate } from 'react-dom/test-utils';
import AddArticleForm from './addArticleForm';

jest.mock('../component/FormJoditEditor', () => () => <div>Abstract editor</div>);

let container;
let root;
let latestData;
const author = { first_name: 'Saikat', last_name: 'Banerjee', designation: 'Professor', affiliation: 'University', corresponding: false, super_no: 2 };

function Harness({ initial }) {
    const [data, setData] = useState(initial);
    latestData = data;
    return <AddArticleForm articleData={data} handleAddInput={e => setData(previous => ({ ...previous, [e.target.name]: e.target.value }))} handleFile={() => {}} />;
}

beforeEach(() => {
    global.IS_REACT_ACT_ENVIRONMENT = true;
    container = document.createElement('div');
    document.body.appendChild(container);
    root = createRoot(container);
});
afterEach(() => {
    act(() => root.unmount());
    container.remove();
});

test('hides legacy inputs without discarding legacy names or affiliations', () => {
    act(() => root.render(<Harness initial={{ aname: 'Name<sup>1</sup>', designation: 'College', authors: [] }} />));
    expect(container.querySelector('input[name="aname"]')).toBeNull();
    expect(container.querySelector('input[name="designation"]')).toBeNull();
    expect(container.querySelector('sup').textContent).toBe('1');
    act(() => Simulate.change(container.querySelector('#article-name'), { target: { name: 'name', value: 'Updated title' } }));
    expect(latestData.aname).toBe('Name<sup>1</sup>');
    expect(latestData.designation).toBe('College');
});

test('retains author designation and supports editing, adding, corresponding and removal', () => {
    act(() => root.render(<Harness initial={{ authors: [{ ...author }] }} />));
    expect(container.querySelector('#author-0-designation').value).toBe('Professor');
    act(() => Simulate.change(container.querySelector('#author-0-designation'), { target: { value: 'Researcher' } }));
    act(() => Simulate.change(container.querySelector('#author-0-corresponding'), { target: { checked: true } }));
    act(() => container.querySelector('.article-add-author').click());
    expect(latestData.authors).toHaveLength(2);
    expect(latestData.authors[0].designation).toBe('Researcher');
    expect(latestData.authors[0].corresponding).toBe(true);
    expect(latestData.authors[1].super_no).toBe(3);
    act(() => container.querySelector('[aria-label="Remove author 1"]').click());
    expect(latestData.authors).toHaveLength(1);
    expect(container.querySelector('#author-0-first_name').value).toBe('');
    expect(latestData.authors[0].super_no).toBe(3);
});

test('all article dates and keyword values remain controlled and labeled', () => {
    act(() => root.render(<Harness initial={{ authors: [], received: '2026-09-01', revised: '2026-09-05', accepted: '2026-09-10', keywords: 'journal' }} />));
    ['received', 'revised', 'accepted', 'published_date'].forEach(name => {
        expect(container.querySelector('#article-' + name).type).toBe('date');
        expect(container.querySelector('label[for="article-' + name + '"]')).not.toBeNull();
    });
    expect(container.querySelector('#article-keywords').value).toBe('journal');
});
