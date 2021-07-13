
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <title>K-Means Applet</title>
    <script src = "js/jquery.min.js"></script>
    <script src = "js/popper.min.js"></script>
    <script src = "js/bootstrap.min.js"></script>
    <script src = "js/display.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">KMeans Applet</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
        </div>
    </div>
    </nav>

<!-- main body page -->
<div class = "container" style = "padding-top:5%">

    <!-- cluster controls -->
    <div class = "container">
        
        <a name = "demo">
            <h4>K-Means Clustering Demo</h4>
        </a>
        <br/>
        <ul class="nav nav-pills">

            <li class="nav-item">
              <a class="nav-link scroll" href="#algo">Algorithm</a>
            </li>

            <li class="nav-item">
                <a class="nav-link scroll" href="#docu">Documentation</a>
            </li>

            <li class="nav-item">
                <a class="nav-link scroll" href="#cite">Citations</a>
            </li>

            <li class="nav-item">
                <a class="nav-link scroll" href="#code">Code</a>
            </li>
            
        </ul>

        <br/>
        <br/>
        <br/>
        <div class="card border-light mb-3">
            <div class="card-header">
                <div class = "row">
                    <div class = "col-xs-9 col-sm-11"><h5>Clustering Parameters</h5></div>
                    <div class = "col-xs-3 col-sm-1">
                        <form method = "post" style = "display:none"><button type="submit" class="btn btn-primary btn-sm" name = "change_data" value = "submit">Change</button></form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class = "container row">
                    <div class = "col-sm-12 col-md-6 col-lg-6 col-xl-6 row">
                        <label for="data_select" class="col-sm-3 col-form-label">Data [x, y]:</label>
                        <div class="col-sm-9">
                            <input type="text" readonly="" class="form-control-plaintext" id="data_select" value="US Arrests [Murder, Assault]">
                        </div>
                    </div>
    
                    <div class = "col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label" for="cluster_select">Number of Clusters:</label>
                            <div class = "col-sm-6">
                                <select class="form-control" id="cluster_select" onchange = "change_clusters(this.value)">
                                <option value = 2>2</option>
                                <option value = 3>3</option>
                                <option value = 4>4</option>
                                <option value = 5>5</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <br/>
                <hr/>
                <div class = "container">
                    <p>Click on <strong>Iterate</strong> to begin updating the cluster assignments.</p>
                </div>
                <div class = "container row">
                    <div class = "col-sm-8">
                        
                        Iterations:
                        <span id = "num_iter">0</span> &nbsp;
                        <span id = "convergence"></span>
                        
                    </div>
                    <div class="btn-group btn-group-toggle col-sm-4" data-toggle="buttons">
                        <button type="button" class="btn btn-primary" id = 'improve_clusters' onclick = "improve_clusters(usarrests, clusters, cluster_cols)">Iterate</button>
                        <button type="button" class="btn btn-outline-primary" id = 'reset_clusters' onclick = "change_clusters(document.getElementById('cluster_select').value)">Reset</button>
                    </div>
                    
                </div>
            </div>
        </div>

        
        

    </div>

    <!--- iteration control-->
    <div class = "container">
        
    </div>
      
    

    <br/>
    <br/>

    <div id ="logs" style = "display:none">

    </div>

    <div class = "row">
        <div class = "col-12 col-sm-12 col-md-7 col-lg-7 col-xl-6" id = "scatter">

        </div>
        <div class = "col-12 col-sm-12 col-md-5 col-lg-5 col-xl-6" id = "centroids">
            
        </div>
    </div>

    <div class = "row">
        <div class = "col-12 col-sm-12 col-md-7 col-lg-7 col-xl-6" id = "rsquare">

        </div>
        <div class = "col-12 col-sm-12 col-md-5 col-lg-5 col-xl-6" id = "stats">
        <h5 style = "font-family: Courier New, monospace; color: #7f7f7f; text-align:center; margin-top:1.8rem; margin-bottom:2.5rem;">Cluster Memberships</h5>
            <div style = "max-height: 10rem">
                <table class="table table-hover" style="font-family: Courier New, monospace; font-size: small; color: #7f7f7f;" id="cluster_table">
                </table>
            </div>
        </div>
    </div>
</div>

<div style = "height:100px"></div>

<!-- notes on algorithm -->
<div class = "container" style = "padding-top:5%" id = "algo">
    <a name = "algo"><h4>Algorithm Notes</h4></a>
    <br/>
    <p>This applet demonstrates a basic implementation of K-means clustering based on Lloyd's algorithm [1]. The algorithm finds a set of means or centroids m<sub>k</sub>, k = 1,2,...,<em>K</em>  such that
    each data point x<sub>i</sub>, i = 1,2,...,<em>N</em> can be partitioned based on their assignment to one of these <em>K</em> centroids. The centroids are continuously updated
    such that the distances between each point and their assigned centroids are minimized.</p>

    <p>We begin by randomly assigning <em>K</em> points from the data to serve as our initial centroids. More efficient initializations leading to faster convergences can be found [2,3],
    but for a simple two-dimensional case we can make do with this basic procedure. Once the <em>K</em> centroids have been initialized, we assign each of the x<sub>i</sub> to a particular centroid, this assignment
    indicated by C<sub>i</sub>, based on which centroid is closest to x<sub>i</sub> by the Euclidean distance.</p>

    <center>
    <img src = "img/kmeans/assignment.png" height = 30px>
    </center>
    <br/>
    <p>After assignment, we obtain an update of the centroid by averaging across all currently assigned data points:</p>

    <center>
        <img src = "img/kmeans/update_step.png" height = 50px>
    </center>
    <br/>

    <p>Then all points are again reassigned to their new clusters based on the updated positions of the centroids. At each step we measure the total (Euclidean) distance between
        each point and their assigned centroid. To control the range of distances, we obtain their logarithms.</p>

    <center>
        <img src = "img/kmeans/objective.png" height = 40px>
    </center>
    <br/>

    <p>Finally, convergence is achieved once the minimum distance per cluster:

    <center><img src = "img/kmeans/objective2.png" height = 25px></center>
    <br/>has changed by no more than a set tolerance level. This applet uses a very small tolerance of 1e-100, but we also set a maximum iteration count of 20 in case the algorithm struggles
    to find a local minimum (which Lloyd's Algorithm has been known to experience in many cases). On the US Arrests data set (see citations), we reach convergence within around 2 to 5 iterations only.</p>
</div>

<div style = "height:100px"></div>

<!-- notes on documentation -->
<div class = "container" style = "padding-top:5%" id = "docu">
    <a name = "docu"><h4>Code Documentation</h4></a>
    <br/>
    <p>This applet was written in Javascript by <a href = "https://github.com/dominicdayta" target="_blank">Dominic Dayta</a>. Plots were produced using the <a href= "https://plotly.com/javascript/" target = "_blank">Plotly Javascript library</a>.</p>
    <p>The user may interact with the applet by clicking on the <strong>Iterate</strong> button, which performs the updating steps of Lloyd's algorithm. Three plots are provided to track the progress of the algorithm towards convergence.</p>
    
    <br/>
    <div class = "row">
        <div class = "col-sm-4">
            <img src="img/kmeans/fig_assignments.png" alt="Cluster Assignments" width = 95%>
        </div>
        <div class = "col-sm-8 container"><p>The first plot visualizes the data points across its dimensions (only two are allowed for this applet) and colored based on their cluster assignments. Each cluster's nearby neighborhood is displayed as a circular backdrop whose radius is proportional to its number of members. As the user moves through iterations,
            these neighborhoods shift in location across the space and so do the data points change cluster assignments. Because of the way we initialize the clusters, it is possible that in the beginning, clusters may overlap or even be completely inscribed within another cluster. This is immediately corrected as soon the algorithm begins updating.
        </p></div>
    </div>
    
    <br/>
    <div class = "row">
        <div class = "col-sm-4">
            <img src="img/kmeans/fig_centroids.png" alt="Cluster Centroids" width = 95%>
        </div>
        <div class = "col-sm-8 container"><p>The second plot displays only the centroids as they are updated across iterations. The data points are provided in the backdrop to provide an idea of how the centroids are moving, and the members that are being included and excluded at each iteration.
        </p></div>
    </div>

    <br/>
    <div class = "row">
        <div class = "col-sm-4">
            <img src="img/kmeans/fig_distances.png" alt="Plot of Cluster Distances to Centroids" width = 95%>
        </div>
        <div class = "col-sm-8 container"><p>The third and last plot tracks the improvements in within-group distances as the algorithm updates. Because the objective of the algorithm is to minimize this value to convergence, the algorithm stops and declares convergence when this line flattens out.
        </p></div>
    </div>
    <div class = "row">
        <div class = "col-sm-4">
            <img src="img/kmeans/fig_members.png" alt="Cluster Statistics" width = 95%>
        </div>
        <div class = "col-sm-8 container"><p>A table is also provided listing at each iteration the clusters and their centroid coordinates, along with the number of members, and the average distance of each member to the centroid.
        </p></div>
    </div>
</div>

<div style = "height:100px"></div>

<!-- citations -->
<div class = "container" style = "padding-top:5%" id = "cite">
    <a name = "docu"><h4>Citations</h4></a>
    <br/>
    <ol>
        <li><strong>Lloyd, Stuart P. (1982).</strong> <a href = "https://cs.nyu.edu/~roweis/csc2515-2006/readings/lloyd57.pdf" target="_blank">"Least squares quantization in PCM."</a> <em>IEEE Transactions on Information Theory.</em> 28 (2): 129–137.</li>
        <li><strong>Hamerly, Greg; Elkan, Charles (2002).</strong> <a href = "https://people.csail.mit.edu/tieu/notebook/kmeans/15_p600-hamerly.pdf" target="_blank"> "Alternatives to the k-means algorithm that find better clusterings."</a> <em>Proceedings of the eleventh international conference on Information and knowledge management (CIKM).</em></li>
        <li><strong>Celebi, M. E.; Kingravi, H. A.; Vela, P. A. (2013).</strong> <a href = "https://www.sciencedirect.com/science/article/abs/pii/S0957417412008767" target="_blank">"A comparative study of efficient initialization methods for the k-means clustering algorithm".</a> <em>Expert Systems with Applications.</em> 40 (1): 200–210.</li>
        <li><strong>McNeil, D. R. (1977)</strong> <em>Interactive Data Analysis</em>. New York: Wiley. [Contains US Arrests data adopted from the R datasets.]</li>
    </ol>
</div>

<!-- code -->
<div class = "container" style = "padding-top:5%" id = "code">
    <a name = "code"><h4>Code</h4></a>
    <br/>
    <p>The full code to this applet can be viewed on <a href = "https://github.com/dominicdayta/kmeans">Github</a>.</p>
</div>

<div style = "height:100px"></div>

<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script src="http://localhost/dominicdayta/js/base.js"></script>
<script src="http://localhost/dominicdayta/js/cluster.js"></script>
<script src="http://localhost/dominicdayta/data/usarrests.js"></script>
<script>
    var num_clusters = 2;
    var logs = document.getElementById('logs');
    var color_range = ["#2109f7","#16ccff","#16fff3","#1696ff","#16ffa9","#16e0ff","#16ffe7","#16ff73"]
    var cluster_cols = ['Murder','Assault'];
    var clusters = init_clusters(k = num_clusters, usarrests, cluster_cols);
    var rsq_list = [];
    var rsq_change = [];
    var tol = 1e-100;
    var max_iter = 20;
    var num_iter = 0;

    change_clusters = function(k){
        // reset parameters
        num_clusters = k;
        rsq_list = [];
        rsq_change = [];
        num_iter = 0;
        document.getElementById('improve_clusters').removeAttribute('disabled');
        document.getElementById('convergence').innerHTML = "";
        clusters = init_clusters(k = num_clusters, usarrests, cluster_cols);
        document.getElementById('num_iter').innerHTML = num_iter;

        // initial cluster assigments
        find_closest(usarrests, clusters, cluster_cols);

        // compute for changes in clusters
        let rsquares = cluster_Rsq(usarrests, clusters, cluster_cols);

        // minimum within-group r square
        let logmax = arrMax(dfColToArray(rsquares, "rsq"));
        rsq_list.push(Math.log(logmax));

        // plot
        generate_scatter(usarrests, clusters);
        generate_centroids(usarrests, clusters);
        generate_line();
        generate_table(clusters, rsquares);

        logs.innerHTML = "<b>Clusters</b><br/>";
        logs.innerHTML += JSON.stringify(clusters);
        logs.innerHTML += "<br/><br/>";
    }

    generate_table = function(clusters, rsquares){
        let cluster_table = document.getElementById('cluster_table');
        let table_content = "<thead><tr><th scope=\"col\">Cluster</th><th scope=\"col\">" + cluster_cols[0] + "</th><th scope=\"col\">" + cluster_cols[1] + "</th><th scope=\"col\">Members</th><th scope=\"col\">Distance</th></tr></thead><tbody>";
        
        for(let i=0; i < clusters.length; i++){
            table_content += "<tr>";
            table_content += "    <th scope=\"row\">Cluster " + Number(i+1) + "</th>";
            table_content += "    <td style=\"text-align:right;\">" + Number(clusters[i][cluster_cols[0]]).toFixed(2) + "</td>";
            table_content += "    <td style=\"text-align:right;\">" + Number(clusters[i][cluster_cols[1]]).toFixed(2) + "</td>";
            table_content += "    <td style=\"text-align:right;\">" + Number(rsquares[i]["members"]).toFixed(0) + "</td>";
            table_content += "    <td style=\"text-align:right;\">" + Number(rsquares[i]["rsq"]).toFixed(2) + "</td>";
            table_content += "</tr>";
        }

        table_content += "</tbody>"
        cluster_table.innerHTML = table_content;
        return true;
    }

    generate_scatter = function(data, clusters, num_iter = 0){
        let scatter_dt = [];
        
        xrange = [
            0.90 * arrMin(dfColToArray(data, cluster_cols[0])),
            1.10 * arrMax(dfColToArray(data, cluster_cols[0]))
        ];

        yrange = [
            0.90 * arrMin(dfColToArray(data, cluster_cols[1])),
            1.10 * arrMax(dfColToArray(data, cluster_cols[1]))
        ];

        for(let i = 0; i < clusters.length; i++){
            cluster_members = subset(data, "_assigned",i);
            cluster_size = 500 * Math.log(cluster_members.length / data.length + 1);

            scatter_dt.push({
                x: dfColToArray([clusters[i]], cluster_cols[0]),
                y: dfColToArray([clusters[i]], cluster_cols[1]),
                showlegend: false,
                mode: 'markers',
                type: 'scatter',
                name: 'Cluster ' + Number(i + 1),
                marker: { 
                    color: color_range[i],
                    opacity: 0.10,
                    size: cluster_size 
                }
            })

            scatter_dt.push({
                x: dfColToArray(cluster_members, cluster_cols[0]),
                y: dfColToArray(cluster_members, cluster_cols[1]),
                showlegend: true,
                mode: 'markers',
                type: 'scatter',
                name: 'Cluster ' + Number(i + 1),
                text: dfColToArray(cluster_members, "State"),
                marker: { 
                    color: color_range[i],
                    size: 10 
                }
            })
        }
        
        let layout = {
            title:'Cluster Assignments (Iteration ' + num_iter + ')',
            font: {
                family: 'Courier New, monospace',
                size: 12,
                color: '#7f7f7f'
            },
	        legend: {
                "orientation": "h"
            },
            xaxis: {fixedrange: true, range: xrange},
            yaxis: {fixedrange: true, range: yrange}
        };

        Plotly.newPlot('scatter', scatter_dt, layout, {displayModeBar: false});
        return true;
    }

    generate_centroids = function(data, clusters, num_iter = 0){
        let scatter_dt = [];
        
        xrange = [
            0.90 * arrMin(dfColToArray(data, cluster_cols[0])),
            1.10 * arrMax(dfColToArray(data, cluster_cols[0]))
        ];

        yrange = [
            0.90 * arrMin(dfColToArray(data, cluster_cols[1])),
            1.10 * arrMax(dfColToArray(data, cluster_cols[1]))
        ];

        for(let i = 0; i < clusters.length; i++){
            cluster_members = subset(data, "_assigned",i);

            // centroids as solid markers
            scatter_dt.push({
                x: dfColToArray([clusters[i]], cluster_cols[0]),
                y: dfColToArray([clusters[i]], cluster_cols[1]),
                showlegend: true,
                mode: 'markers',
                type: 'scatter',
                name: 'Cluster ' + Number(i + 1),
                marker: { 
                    color: color_range[i],
                    size: 10 
                }
            })

            // data points as translucent markers
            scatter_dt.push({
                x: dfColToArray(cluster_members, cluster_cols[0]),
                y: dfColToArray(cluster_members, cluster_cols[1]),
                showlegend: false,
                mode: 'markers',
                type: 'scatter',
                name: 'Cluster ' + Number(i + 1),
                text: dfColToArray(cluster_members, "State"),
                marker: { 
                    color: color_range[i],
                    opacity: 0.2,
                    size: 7 
                }
            })
        }
        
        let layout = {
            title:'Centroids (Iteration ' + num_iter + ')',
            font: {
                family: 'Courier New, monospace',
                size: 12,
                color: '#7f7f7f'
            },
	        legend: {
                "orientation": "h"
            },
            xaxis: {fixedrange: true, range: xrange},
            yaxis: {fixedrange: true, range: yrange}
        };

        Plotly.newPlot('centroids', scatter_dt, layout, {displayModeBar: false});
        return true;
    }

    generate_line = function(){

        let rsquare_dt = [{
            y: rsq_list,
            x: seq_along(rsq_list),
            type: 'line',
            marker:{
                color: color_range[0]
            }
        }];

        let layout = {
            title:'Maximum Distance to Centroid',
            font: {
                family: 'Courier New, monospace',
                size: 12,
                color: '#7f7f7f'
            },
            showlegend: false,
            xaxis: {fixedrange: true},
            yaxis: {fixedrange: true},
            height: 400
        };

        Plotly.newPlot('rsquare', rsquare_dt, layout, {displayModeBar: false});
    }

    improve_clusters = function(data, clust_list, cols){
        let num_iter = Number(document.getElementById('num_iter').innerHTML);
        num_iter += 1;

        iter = iterate(data, clust_list, cols);
        clusters = iter.centroids;

        // compute for changes in clusters
        let rsquares = cluster_Rsq(data, clusters, cols);

        // update varaiables
        let current_rsq = iter['fits'].ave_rsq;
        rsq_list.push(current_rsq);
        let rsq_change = 100*(rsq_list[num_iter - 1] - rsq_list[num_iter])/Math.max(1,rsq_list[num_iter - 1]);

        if((Math.abs(rsq_change)< tol && current_rsq == arrMin(rsq_list)) || num_iter >= max_iter){
            document.getElementById('improve_clusters').setAttribute('disabled','');
            document.getElementById('convergence').innerHTML = "Algorithm has converged!";
        }

        // plots
        generate_scatter(data, clusters, num_iter);
        generate_centroids(data, clusters, num_iter);
        generate_line();
        generate_table(clusters, rsquares);

        document.getElementById('num_iter').innerHTML = num_iter;
        logs.innerHTML = "<b>Clusters</b><br/>";
        logs.innerHTML += JSON.stringify(iter.centroids);
        logs.innerHTML += "<br/><br/>";
    }

    change_clusters(2);
</script><!-- footer -->
<footer class="bg-light text-center text-lg-start mt-auto">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
          © 2021 Copyright:
          <a class="text-dark" href="https://github.com/dominicdayta/">Dominic Dayta</a>
        </div>
        <!-- Copyright -->
    </footer>
</body>
</html>