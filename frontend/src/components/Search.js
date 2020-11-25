import React, { useEffect, useState } from 'react';
import { Button, Dimmer, Form, Icon, Input, Image, List, Loader, Select } from 'semantic-ui-react';
import i18n from 'i18next';
import { useTranslation } from 'react-i18next';
import { getByCategory, getByWord, getCategories, getRandomly } from './../services/joke';
import 'semantic-ui-css/semantic.min.css';

function Search() {
  const [jokes, setJokes] = useState([]);
  const [categoryOptions, setCategoryOptions] = useState([]);
  const [wordSearchDisabled, setWordSearchDisabled] = useState(true);
  const [categorySearchDisabled, setCategorySearchDisabled] = useState(true);
  const [searchType, setSearchType] = useState(null);
  const [word, setWord] = useState(null);
  const [category, setCategory] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const languageOptions = [
    { text: 'English', value: 'en' },
    { text: 'Español', value: 'es' },
  ];
  const searchTypeOptions = [
    { text: 'Words', value: 'words' },
    { text: 'Categories', value: 'categories' },
    { text: 'Randomly', value: 'randomly' },
  ];

  useEffect(() => {
    let mounted = true;
    getCategories()
      .then(categories => {
        if (mounted) {
          setCategoryOptions(categories);
        }
      });
      return () => mounted = false;
  }, []);

  const { t } = useTranslation();

  function handleLanguageChange(e, d) {
    i18n.changeLanguage(d.value);
  }

  function handleSearchTypeChange(e, d) {
    // eslint-disable-next-line
    switch (d.value) {
      case 'words':
        setWordSearchDisabled(false);
        setCategorySearchDisabled(true);
        setSearchType('words');
        break;
      case 'categories':
        setWordSearchDisabled(true);
        setCategorySearchDisabled(false);
        setSearchType('categories');
        break;
      case 'randomly':
        setWordSearchDisabled(true);
        setCategorySearchDisabled(true);
        setSearchType('randomly');
    }
  }

  function handleSearch() {
    // very ugly solution :S
    setIsLoading(true);
    switch (searchType) {
      case 'words':
        getByWord(word)
          .then(jokes => {
            setJokes(jokes);
            setIsLoading(false);
          });
        break;
      case 'categories':
        getByCategory(category)
          .then(jokes => {
            setJokes(jokes);
            setIsLoading(false);
          });
        break;
      case 'randomly':
        getRandomly()
          .then(joke => {
            setJokes([joke]);
            setIsLoading(false);
          });
        break;
      default:
        setIsLoading(false);
        alert(t('no_search_type_selected'));
    }
  }

  const listJokes = jokes.map((joke) =>
    <List.Item>
      <Image avatar src={process.env.PUBLIC_URL + '/chucknorris_logo.png'} />
      <List.Content>
        <List.Header>{joke.value}</List.Header>
      </List.Content>
    </List.Item>
  );

  return (
    <div className='Search'>
      <Dimmer active={isLoading} inverted>
        <Loader size='large'>{t('loading')}</Loader>
      </Dimmer>

      <Form size='large'>
        <Form.Group>
          <Form.Field
            control={Select}
            options={languageOptions}
            placeholder={t('select_your_language')}
            width='two'
            search
            onChange={handleLanguageChange}
          />
          <Form.Field
            control={Select}
            options={searchTypeOptions}
            placeholder={t('select_a_search_type')}
            width='two'
            search
            onChange={handleSearchTypeChange}
          />
          <Form.Field
            control={Input}
            placeholder={t('type_your_search_word')}
            width='two'
            disabled={wordSearchDisabled}
            onChange={(e, d) => setWord(d.value)}
          />
          <Form.Field
            control={Select}
            options={categoryOptions}
            placeholder={t('select_a_category')}
            width='two'
            search
            disabled={categorySearchDisabled}
            onChange={(e, d) => setCategory(d.value)}
          />
          <Form.Button
            control={Button}
            animated
            primary
            size='large'
            onClick={handleSearch}
          >
            <Button.Content visible>{t('search_button')}</Button.Content>
            <Button.Content hidden>
              <Icon name='arrow right' />
            </Button.Content>
          </Form.Button>
        </Form.Group>

      </Form>

      <List divided>
        {listJokes}
      </List>
    </div>
  );
}

export default Search;
