const menuButton = document.querySelector('.menu');
      const navigation = document.querySelector('nav');

      menuButton.addEventListener('click', () => {
        const isOpen = menuButton.getAttribute('aria-expanded') === 'true';

        menuButton.setAttribute('aria-expanded', String(!isOpen));
        navigation.classList.toggle('open', !isOpen);
        menuButton.firstElementChild.textContent = isOpen ? 'menu' : 'close';
      });

      navigation.addEventListener('click', (event) => {
        if (event.target.matches('a')) {
          navigation.classList.remove('open');
          menuButton.setAttribute('aria-expanded', 'false');
          menuButton.firstElementChild.textContent = 'menu';
        }
      });

      const missionChart = document.querySelector('.mission-chart');
      const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      if (missionChart && !prefersReducedMotion) {
        const chartLine = missionChart.querySelector('.chart-line');
        const chartArea = missionChart.querySelector('.chart-area');
        const chartPointGroups = [...missionChart.querySelectorAll('.chart-point')];
        const chartPointCircles = chartPointGroups.map((point) => point.querySelector('circle'));
        const chartTargets = [60, 62, 44, 44, 30, 31, 63, 60];
        const chartDelays = [0, .06, .12, .2, .36, .48, .64, .74];
        const chartPointIndexes = [0, 3, 5, 7];
        const chartBaseline = 188;
        const chartDuration = 2600;
        let chartHasAnimated = false;

        function chartProgress(progress, delay) {
          const localProgress = Math.max(0, Math.min(1, (progress - delay) / .28));
          return 1 - Math.pow(1 - localProgress, 3);
        }

        function chartPath(values) {
          return `M62 ${values[0]} C112 ${values[1]} 137 ${values[2]} 184 ${values[3]} S270 ${values[4]} 324 ${values[5]} S425 ${values[6]} 486 ${values[7]}`;
        }

        function prepareMissionChart() {
          const baselineValues = chartTargets.map(() => chartBaseline);
          const baselinePath = chartPath(baselineValues);

          chartLine.setAttribute('d', baselinePath);
          chartLine.style.strokeDasharray = '1';
          chartLine.style.strokeDashoffset = '1';
          chartArea.setAttribute('d', `${baselinePath} L486 188 L62 188 Z`);
          chartArea.style.opacity = '0';

          chartPointCircles.forEach((circle) => circle.setAttribute('cy', String(chartBaseline)));
          chartPointGroups.forEach((point) => { point.style.opacity = '0'; });
        }

        function animateMissionChart() {
          if (chartHasAnimated) return;
          chartHasAnimated = true;
          const animationStart = performance.now();

          function drawFrame(currentTime) {
            const progress = Math.min(1, (currentTime - animationStart) / chartDuration);
            const currentValues = chartTargets.map((target, index) => {
              const easedProgress = chartProgress(progress, chartDelays[index]);
              return chartBaseline + ((target - chartBaseline) * easedProgress);
            });
            const currentPath = chartPath(currentValues);

            chartLine.setAttribute('d', currentPath);
            chartLine.style.strokeDashoffset = String(1 - progress);
            chartArea.setAttribute('d', `${currentPath} L486 188 L62 188 Z`);
            chartArea.style.opacity = String(Math.min(1, progress * 1.35));

            chartPointIndexes.forEach((valueIndex, pointIndex) => {
              const pointProgress = chartProgress(progress, chartDelays[valueIndex]);
              chartPointCircles[pointIndex].setAttribute('cy', String(currentValues[valueIndex]));
              chartPointGroups[pointIndex].style.opacity = String(Math.min(1, pointProgress * 1.7));
            });

            if (progress < 1) {
              window.requestAnimationFrame(drawFrame);
              return;
            }

            chartLine.style.strokeDashoffset = '0';
            chartArea.style.opacity = '1';
            chartPointGroups.forEach((point) => { point.style.opacity = '1'; });
          }

          window.requestAnimationFrame(drawFrame);
        }

        prepareMissionChart();

        if ('IntersectionObserver' in window) {
          const chartObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
              if (!entry.isIntersecting) return;

              animateMissionChart();
              observer.unobserve(missionChart);
            });
          }, { threshold: .35 });

          chartObserver.observe(missionChart);
        } else {
          animateMissionChart();
        }
      }

      const teamTree = document.querySelector('.team-tree-preview');

      if (teamTree) {
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
          teamTree.classList.add('is-visible');
        } else {
          const teamTreeObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
              if (!entry.isIntersecting) return;

              teamTree.classList.add('is-visible');
              observer.unobserve(teamTree);
            });
          }, { threshold: .18 });

          teamTreeObserver.observe(teamTree);
        }
      }

      document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', (event) => {
          event.preventDefault();
          const button = event.currentTarget.querySelector('button');
          const input = event.currentTarget.querySelector('input');

          button.textContent = 'Subscribed';
          input.value = '';
        });
      });
