<?php


namespace App\TwigExtension;


use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Pool;
use App\Entity\Team;
use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * RaceResult
 * RaceResult.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 04/06/2021
 */
class RaceResult extends AbstractExtension
{
    /**
     * @var RaceRepository
     */
    protected RaceRepository $raceRepository;

    public function getFilters(): array
    {
        return [
            new TwigFilter('bestLap', [$this, 'getBestLap']),
            new TwigFilter('podium', [$this, 'getPodium']),
            new TwigFilter('bestTeam', [$this, 'getBestTeam']),
            new TwigFilter('bestProgression', [$this, 'getBestProgression']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generalRanking', [$this, 'getGeneralRanking']),
        ];
    }

    /**
     * @param PersistentCollection|DriverRace[] $driverRaces
     * @return string
     */
    public function getBestTeam($driverRaces): string
    {
        $teamPoints = [];
        $podium = $this->getPodium($driverRaces, true);
        foreach ($podium as $results) {
            foreach ($results as $result) {
                if ($result->getDriver()->getTeam() instanceof Team) {
                    if (isset($teamPoints[$result->getDriver()->getTeam()->getName()])) {
                        $teamPoints[$result->getDriver()->getTeam()->getName()] += $result->getPoints();
                    } else {
                        $teamPoints[$result->getDriver()->getTeam()->getName()] = $result->getPoints();
                    }
                }
            }
        }
        return array_search(max($teamPoints), $teamPoints, true);
    }

    /**
     * @param PersistentCollection|DriverRace[] $driverRaces
     * @return array
     */
    public function getBestProgression($driverRaces): array
    {
        $driversProgression = [];
        $podium = $this->getPodium($driverRaces, true);
        foreach ($podium as $pool => $results) {
            foreach ($results as $finalRank => $result) {
                $driversProgression[$result->getDriver()->getPsn()] = $finalRank - $result->getStartPosition();
            }
        }
        return ['driver' => array_search(min($driversProgression), $driversProgression, true), 'progression' => min($driversProgression)];
    }

    /**
     * @param int $userGroupId
     * @return array
     */
    public function getGeneralRanking(int $userGroupId): array
    {
        $output = [];
        $output['ranking'] = [];
        $output['driver_race_positions'] = [];
        $output['driver_progressions'] = [];
        $output['teams'] = [];
        $output['team_progressions'] = [];
        $output['maker_points'] = [];
        $output['best_driver'] = null;
        $races = $this->raceRepository->createQueryBuilder('race')
            ->where('race.date < :now')
            ->andWhere('race.userGroup = :userGroupId')
            ->setParameter('now', new \DateTime())
            ->setParameter('userGroupId', $userGroupId)
            ->orderBy('race.date', 'asc')
            ->getQuery()
            ->getResult();

        $racesResults = [];
        foreach ($races as $i => $race) {
            $racesResults[] = [
                'race' => $race,
                'results' => $this->getRaceResults($race->getDriverRaces())
            ];

        }
        foreach ($racesResults as $raceData) {
            foreach ($raceData['results'] as $pool => $driverResults) {
                foreach ($driverResults as $finishPosition => $driverResult) {
                    if ($driverResult instanceof DriverRace) {
                        $psn = $driverResult->getDriver()->getPsn();
                        $finalPosition = $driverResult->getFinalPosition();
                        $delta = $finalPosition - $driverResult->getStartPosition();
                        $output['driver_race_positions'][$psn][$driverResult->getRace()->getName()] = [
                            'start' => $driverResult->getStartPosition(),
                            'finish' => $finalPosition,
                            'delta' => $delta
                        ];
                        $points = $driverResult->getPoints();
                        $team = $driverResult->getDriver()->getTeam();
                        $poolFactor = max($driverResult->getPool()->getPoints());
                        if ($team instanceof Team) {
                            $output['team_driver'][$psn] = $team->getName();
                            $output['teams'][$team->getName()]['delta'][] = $delta;
                            if (isset($output['teams'][$team->getName()]['nb_race'])) {
                                $output['teams'][$team->getName()]['nb_race'] += 1;
                            } else {
                                $output['teams'][$team->getName()]['nb_race'] = 1;
                            }
                            if (isset($output['teams'][$team->getName()]['points'])) {
                                $output['teams'][$team->getName()]['points'] += $points * $poolFactor;
                            } else {
                                $output['teams'][$team->getName()]['points'] = $points * $poolFactor;
                            }
                        }
                        if (isset($output['nb_race'][$psn])) {
                            ++$output['nb_race'][$psn];
                        } else {
                            $output['nb_race'][$psn] = 1;
                        }
                        $output['driver_race_points'][$psn][] = $points;
                        if (isset($output['ranking'][$psn]['points'])) {
                            $output['ranking'][$psn]['points'] += $points;
                        } else {
                            $output['ranking'][$psn]['points'] = $points;
                        }
                        $maker = $driverResult->getCar()->getMaker()->getName();
                        if (isset($output['maker_points'][$maker])) {
                            $output['maker_points'][$maker] += $points;
                        } else {
                            $output['maker_points'][$maker] = $points;
                        }
                        $pool = $driverResult->getPool()->getName();
                        $output['ranking'][$psn]['current_pool'] = $pool;
                        if(isset($output['ranking'][$psn]['pool_evolution'])){

                        $prevIndex = count($output['ranking'][$psn]['pool_evolution']) - 1;
                        } else {
                            $prevIndex = 0;
                        }
                        if(isset($output['ranking'][$psn]['pool_evolution'][$prevIndex]) && $output['ranking'][$psn]['pool_evolution'][$prevIndex] === $pool){
                            //var_dump($output['ranking'][$psn]['pool_evolution']);
                        } else {
                            $output['ranking'][$psn]['pool_evolution'][] = $pool;
                        }
                        $output['ranking'][$psn]['pool_priority_evolution'][] = $poolFactor;
                    }
                }
            }
        }

        if(isset($output['ranking']) && array_key_exists(0,$output['ranking'])){
            $output['best_driver'] = array_keys($output['ranking'])[0];
        }
        foreach ($output['driver_race_positions'] as $psn => $data) {
            $deltaTotal = 0;
            foreach ($data as $datum) {
                $deltaTotal += $datum['delta'];
            }
            $output['driver_progressions'][$psn] = $deltaTotal * $output['nb_race'][$psn] / array_sum($output['ranking'][$psn]['pool_priority_evolution']);
            if(isset($output['team_driver'][$psn])) {
                if (isset($output['team_progressions'][$output['team_driver'][$psn]])) {
                    $output['team_progressions'][$output['team_driver'][$psn]] = $output['driver_progressions'][$psn];
                } else {
                    $output['team_progressions'][$output['team_driver'][$psn]] = $output['driver_progressions'][$psn];
                }
            }
        }
        $output['best_driver_rate'] = (is_array($output['driver_progressions']) && !empty($output['driver_progressions']))??max($output['driver_progressions']);
        $output['best_team'] = array_key_exists(0, $output['teams'])?array_keys($output['teams'])[0]:null;
        $output['best_team_progression'] = (is_array($output['team_progressions']) && !empty($output['team_progressions']))??array_search(max($output['team_progressions']), $output['team_progressions'], true);
        $output['best_driver_progression'] = (is_array($output['driver_progressions']) && !empty($output['driver_progressions']))??array_search(max($output['driver_progressions']), $output['driver_progressions'], true);
        uasort($output['ranking'], static function ($a, $b) {
            return $a['points'] < $b['points'];
        });
        uasort($output['teams'], static function ($a, $b) {
            return $a['points'] < $b['points'];
        });
        asort($output['team_progressions'], SORT_DESC);
        $output['best_maker'] = (is_array($output['maker_points']) && !empty($output['maker_points']))??array_search(max($output['maker_points']), $output['maker_points'], true);
        return $output;
    }

    public function getRaceResults(PersistentCollection $driverRaces): array
    {
        return $this->getPodium($driverRaces, true);
    }

    /**
     * @param PersistentCollection|DriverRace[] $driverRaces
     * @param bool $full
     * @return DriverRace[]
     */
    public function getPodium($driverRaces, bool $full = false): array
    {
        $poolPriority = [];
        $podiumByPool = [];
        $driverRacesByPool = [];
        foreach ($driverRaces as $i => $driverRace) {
            if ($driverRace->hasBennValidated() && ($driverRace->isValid() || $driverRace->isValidButDisconnected() || $driverRace->isValidButMissing())) {
                $driverRacesByPool[$driverRace->getPool()->getName()][$i] = $driverRace;
            }
        }

        foreach ($driverRacesByPool as $pool => $poolDriverRaces) {
            $poolPriority[$pool] = current($poolDriverRaces)->getPool();
            $tmp = $poolDriverRaces;
            if (!empty($tmp)) {
                uasort(
                    $tmp,
                    static function (DriverRace $a, DriverRace $b) {
                        return $a->getPoints() < $b->getPoints();
                    }
                );
                $tmp = array_values($tmp);
                $output = [];
                foreach ($tmp as $index => $driverRace) {
                    if ($full === false && $index > 2) {
                        break;
                    }
                    $output[] = $driverRace;
                }
                $podiumByPool[$pool] = $output;

            }
        }
        uasort($poolPriority, static function (Pool $a, Pool $b) {
            return $a->getPriority() > $b->getPriority();
        });
        foreach (array_keys($poolPriority) as $pool) {
            $poolPriority[$pool] = $podiumByPool[$pool];
        }

        return $poolPriority;
    }

    /**
     * @param PersistentCollection|DriverRace[] $driverRaces
     * @return DriverRace
     */
    public function getBestLap($driverRaces): ?DriverRace
    {
        if ($driverRaces instanceof PersistentCollection) {
            $driverRaces = $driverRaces->toArray();
        }
        $driverRaces = array_filter($driverRaces, static function (DriverRace $a) {
            return $a->getBestLap() !== '00:00:00.000' && $a->hasBeenValidated();
        });
        usort($driverRaces, static function (DriverRace $a, DriverRace $b) {
            return (int)str_replace(':', '', $a->getBestLap()) > (int)str_replace(':', '', $b->getBestLap());
        });
        return $driverRaces[0]??null;
    }

    /**
     * @param RaceRepository $raceRepository
     * @return RaceResult
     */
    public function setRaceRepository(RaceRepository $raceRepository): RaceResult
    {
        $this->raceRepository = $raceRepository;
        return $this;
    }
}