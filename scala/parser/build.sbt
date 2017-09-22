import Dependencies._
import sbt.Keys.libraryDependencies

lazy val root = (project in file(".")).
  settings(
    inThisBuild(List(
      organization := "com.example",
      scalaVersion := "2.12.1",
      version := "0.1.0-SNAPSHOT"
    )),
    name := "Hello",
    libraryDependencies += "com.opencsv" % "opencsv" % "4.0",
    libraryDependencies += "com.orangesignal" % "orangesignal-csv" % "2.2.1"
    //    libraryDependencies += "net.sf.supercsv" % "super-csv" % "2.4.0"
    //    libraryDependencies += "com.github.tototoshi" %% "scala-csv" % "1.3.5"
  )
