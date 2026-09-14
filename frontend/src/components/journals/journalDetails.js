import React, { useEffect, useState, useMemo } from 'react';
import PropTypes from 'prop-types';
import SwipeableViews from 'react-swipeable-views-react-18-fix';
import { useTheme } from '@mui/material/styles';
import AppBar from '@mui/material/AppBar';
import Tabs from '@mui/material/Tabs';
import Tab from '@mui/material/Tab';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import Card from '@mui/material/Card';
import { CardActions } from '@mui/material';
import { Button } from '@mui/material';
import Archive from './archive';
import EditorialBoard from './editorialBoard';
import LatestArticle from '../home/latestArticle';
import { useNavigate, Outlet } from 'react-router-dom';
import LoadingSkelton from '../common/skelton';
import LinearProgress from '@mui/material/LinearProgress';

import { useParams } from 'react-router-dom';
import { useLocation } from 'react-router-dom';

import api from '../../API/api';

function TabPanel(props) {
  

  const { children, value, index, ...other } = props;

  return (
    <div
      role="tabpanel"
      hidden={value !== index}
      id={`full-width-tabpanel-${index}`}
      aria-labelledby={`full-width-tab-${index}`}
      {...other}
    >
      {value === index && (
        <Box sx={{ p: 3 }}>
          <Typography>{children}</Typography>
        </Box>
      )}
    </div>
  );
}

TabPanel.propTypes = {
  children: PropTypes.node,
  index: PropTypes.number.isRequired,
  value: PropTypes.number.isRequired,
};

function a11yProps(index) {
  return {
    id: `full-width-tab-${index}`,
    'aria-controls': `full-width-tabpanel-${index}`,
  };
}

export default function FullWidthTabs() {
  const location = useLocation();
  const navitate = useNavigate();
  const params = useParams();

  const queryParams = new URLSearchParams(location.search);
  const tab = queryParams.get('view'); // 'about'
  console.log("location ", tab);

  const [tabView, setTabView] = useState(queryParams.get('view'));
  const [journal, setJournal] = useState();
  const [issues, setIssues] = useState([]);
  const [volume, setVolume] = useState([]);
  const [editors, setEditors] = useState();
  const [latest, setLatest] = useState();
  const [latestJournalArticle, setLatestJournalArticle] = useState();
  const [chief, setChief] = useState();
  const [indexing, setIndexing] = useState();

  const [load, setLoad] = useState(false);

  const theme = useTheme();
  const [value, setValue] = React.useState(1);


  const tabsNumber = [
    {
      name: 'about',
      id: 0
    },
    {
      name: 'current_issue',
      id: 1
    },
    {
      name: 'archive',
      id: 2
    },
    {
      name: 'editorial_board',
      id: 3
    },
  ]

  useEffect(()=>{
    let tab = tabsNumber.find((t) => t.name === tabView);
    setValue(tab && tab.id || 1)

    // if(tabView==='about'){
    // }
    // if(tabView==='current_issue'){
    //   setValue(1)
    // }
  },[tabView])

  const handleChange = (event, newValue) => {
    setValue(newValue);
    let name = tabsNumber.find((t) => t.id === newValue).name;

    navitate(`?view=${name}`)
    // setTabView(newValue)
    // console.log("here 1 ", newValue);
    
  };
  
  const handleChangeIndex = (index) => {
    setValue(index);
    // console.log("here 2 ", index);
  };



  useEffect(() => {
    journalDetails();
    getIndexingById();
  }, [params.journal]);

  const getIndexingById = () => {
    api.getIndexingById(params.journal)
      .then((res) => {
        console.log("indexing->", res);
        setIndexing(res.data.indexing);
      })
  }

  const journalDetails = () => {
    setLoad(true)
    api.journalDetails(params.journal)
      .then((res) => {

        setLoad(false);
        console.log("all", res);
        setJournal(res.data.journal);
        setIssues(res.data.issues);
        setVolume(res.data.volume)
        setEditors(res.data.editors)
        setLatest(res.data.latest_artricle)
        setChief(res.data.chief)

        setLatestJournalArticle(res.data.latest_journal_article)

        console.log("last", res.data.issues.at(-1)['issues']);

      })
      .catch((err) => {
        setLoad(false);
        console.log(err.response);
      })

  }

  return (
    <>
      {load ?
        <>
          <LinearProgress />

          <LoadingSkelton />

        </> :

        <div className='container '>


          <Card>
            {journal &&
              <div className='col-md-12'>
                <div className='bg-primary p-2 text-white botton-line'>
                  <h4 className='text-center'>
                    {journal.j_name}
                  </h4>
                </div>

                <Card>
                  <div className='m-2'>
                    <div className="row">
                      <div className="col-md-3 d-flex flex-column justify-content-center">
                        <img src={`${process.env.REACT_APP_BACKEND}assets/journal/${journal.photo}`} className="card-img" alt="Banner" />
                      </div>

                      <div className="col-md-9 d-flex flex-column justify-content-center">
                        <div className=' m-2'>
                          <span className='text-success'> <strong> {journal.j_name} </strong> </span>

                          <div> <strong> Abbr. Title:</strong> {journal.abbr_title}</div>
                          {journal.j_name == 'ISAR Journal of Economics and Business Management' &&
                            <div>
                              <strong>
                                Subject:
                              </strong> Business management/ public relation
                            </div>
                          }

                          <div> <strong> ISSN(Online):</strong>  {journal.issn}</div>
                          <div> <strong> Publisher:</strong> ISAR Publisher </div>
                          <div> <strong> Chief Editor: </strong> {chief && chief.name} </div>
                          <div> <strong> Country of origin: </strong> {journal.country_of_origin} </div>
                          <div> <strong> Language: </strong> {journal.language} </div>
                          <div> <strong> Frequency: </strong> {journal.frequency} </div>
                          <div> <strong> Format: </strong> Online </div>
                          <div> <strong> Journal starting year: </strong> {journal.starting_year} </div>
                          <div className='text-right pt-2'>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </Card>
              </div>
            }
          </Card>




          <Box sx={{ bgcolor: 'background.paper' }}>
            <AppBar position="static">
              <Tabs
                value={value}
                onChange={handleChange}
                indicatorColor="secondary"
                textColor="inherit"
                variant="fullWidth"
                aria-label="full width tabs example"
              >
                <Tab label="About" {...a11yProps(0)} style={{ minWidth: "20%", fontSize: "10px" }} />
                <Tab label="Current Issue" {...a11yProps(1)} style={{ minWidth: "20%", fontSize: "10px" }} />
                <Tab label="Archive" {...a11yProps(2)} style={{ minWidth: "20%", fontSize: "10px" }} />
                <Tab label="Editorial Panel" {...a11yProps(3)} style={{ minWidth: "20%", fontSize: "10px" }} />
                {/* <Tab label="Indexing" {...a11yProps(4)} style={{ minWidth: "20%", fontSize: "10px" }} /> */}
                {/* <Tab label="Indexing" {...a11yProps(5)} /> */}
              </Tabs>
            </AppBar>
            <SwipeableViews
              axis={theme.direction === 'rtl' ? 'x-reverse' : 'x'}
              index={value}
              onChangeIndex={handleChangeIndex}
            >



              
              <TabPanel value={value} index={0} dir={theme.direction}>

                <>
                  <div className='col-md-12'>
                    {journal &&

                      <Card>
                        <div className='m-3'>
                          <div>
                            <strong> <u> Aim And Scope</u></strong>
                          </div>
                          <div className='text-justify'>
                            <div dangerouslySetInnerHTML={{ __html: journal.aim_and_scope }} />
                          </div>
                        </div>
                      </Card>
                    }
                  </div>
                </>
              </TabPanel>




              <TabPanel value={value} index={1} dir={theme.direction}>
                <div>

                  <Button variant="outlined" onClick={() => navitate(`/Articles/${issues.at(-1)['issue_id']}`)} className='m-1' size="small">
                    {volume.length && volume.at(0)['name']} - {issues.length && issues.at(-1)['issues']}
                  </Button>

                </div>
              </TabPanel>



              <TabPanel value={value} index={2} dir={theme.direction}>
                <Archive issues={issues} volume={volume} />
              </TabPanel>



              <TabPanel value={value} index={3} dir={theme.direction}>
                <EditorialBoard editors={editors} />
              </TabPanel>



              <TabPanel value={value} index={4} dir={theme.direction}>
                <>
                  {indexing &&
                    <Card>
                      <div className='bg-primary p-2 text-white botton-line'>
                        INDEXING
                      </div>
                      <CardActions>
                        <div className='col-12'>
                          <div className='row'>
                            {indexing && indexing.map((ind) => (
                              <div className='col-6 text-center p-1'>
                                <a href={ind.link} target='_blank'>
                                  <img src={`${process.env.REACT_APP_BACKEND}assets/indexing/${ind.img}`} width='150' />
                                </a>
                              </div>
                            ))}
                          </div>
                        </div>
                      </CardActions>
                    </Card>
                  }
                </>
              </TabPanel>

            </SwipeableViews>
          </Box>

          <LatestArticle latest={latestJournalArticle} />
        </div>
      }
    </>
  );
}
